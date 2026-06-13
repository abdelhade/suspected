# 06 — مخطط قاعدة البيانات

## 1. الجداول الرئيسية

```
persons                    ← المسجّل (الجدول المركزي)
person_aliases
person_addresses
person_media
person_convictions
person_vehicles
person_associates          ← علاقة many-to-many بين persons
person_weapons
person_type_history

reports
report_persons
report_weapons

weapons
crime_types
crime_methods
charge_types

users                      ← موجود (Laravel default)
roles / permissions        ← Spatie
audit_logs / activity_log  ← Spatie Activity
```

---

## 2. جدول `persons` (الأعمدة الأساسية)

```sql
persons
├── id                    BIGINT PK
├── file_number           VARCHAR UNIQUE     -- REG-2026-00042
├── person_type           ENUM               -- visitor, registered_a, registered_b
├── status                ENUM               -- draft, active, wanted, detained, archived
│
├── -- القسم 1: تعريف
├── full_name             VARCHAR
├── full_name_en          VARCHAR NULL
├── nickname              VARCHAR NULL
├── gender                ENUM
├── birth_date            DATE NULL
├── approximate_age       INT NULL
├── birth_place           VARCHAR NULL
├── nationality           VARCHAR
├── second_nationality    VARCHAR NULL
├── religion              VARCHAR NULL
├── marital_status        ENUM NULL
├── children_count        INT NULL
│
├── -- القسم 3: وثائق
├── national_id           VARCHAR NULL       -- encrypted
├── passport_number       VARCHAR NULL       -- encrypted
├── passport_country      VARCHAR NULL
├── passport_expiry       DATE NULL
├── residency_number      VARCHAR NULL       -- encrypted
├── residency_type        ENUM NULL
├── driving_license       VARCHAR NULL       -- encrypted
├── military_id           VARCHAR NULL       -- encrypted
├── tax_id                VARCHAR NULL       -- encrypted
│
├── -- القسم 4: جسدي
├── height_cm             INT NULL
├── weight_kg             INT NULL
├── skin_color            ENUM NULL
├── hair_color            VARCHAR NULL
├── hair_type             ENUM NULL
├── eye_color             VARCHAR NULL
├── face_shape            VARCHAR NULL
├── facial_hair           VARCHAR NULL
├── distinguishing_marks  TEXT NULL
├── usual_clothing        TEXT NULL
├── gait_description      VARCHAR NULL
├── accent                VARCHAR NULL
├── physical_description  TEXT NULL
├── disability            VARCHAR NULL
│
├── -- القسم 5: اتصال
├── phone_1               VARCHAR NULL       -- encrypted
├── phone_2               VARCHAR NULL       -- encrypted
├── messaging_id          VARCHAR NULL       -- encrypted
├── email                 VARCHAR NULL       -- encrypted
├── social_accounts       JSON NULL
│
├── -- القسم 6: عمل
├── education_level       ENUM NULL
├── education_field       VARCHAR NULL
├── occupation            VARCHAR NULL
├── employer              VARCHAR NULL
├── work_address          TEXT NULL
├── income_range          ENUM NULL
├── income_source         VARCHAR NULL
│
├── -- القسم 7: خطورة
├── risk_level            ENUM               -- low, medium, high, critical
├── primary_charge_id     BIGINT FK NULL
├── charge_tags           JSON NULL
├── gang_affiliation      VARCHAR NULL
├── gang_role             VARCHAR NULL
├── substance_involvement ENUM NULL
├── armed_habitually      BOOLEAN DEFAULT false
├── violent_tendency        ENUM NULL
├── risk_notes            TEXT NULL
│
├── -- القسم 8: قانوني
├── is_wanted             BOOLEAN DEFAULT false
├── warrant_number        VARCHAR NULL
├── warrant_date          DATE NULL
├── warrant_issuer        VARCHAR NULL
├── is_detained           BOOLEAN DEFAULT false
├── detention_place       VARCHAR NULL
├── detention_start       DATE NULL
├── criminal_record_summary TEXT NULL
├── prior_convictions_count INT NULL
├── under_surveillance    BOOLEAN DEFAULT false
├── surveillance_start    DATE NULL
│
├── -- القسم 14: ملاحظات
├── general_notes         TEXT NULL
├── internal_notes        TEXT NULL
├── known_behaviors       TEXT NULL
├── frequent_locations    TEXT NULL
├── escape_methods        TEXT NULL
├── intel_notes           TEXT NULL           -- encrypted
│
├── -- القسم 15: إداري
├── origin_report_id      BIGINT FK NULL
├── expires_at            DATE NULL           -- للزائر
├── created_by            BIGINT FK
├── updated_by            BIGINT FK NULL
├── approved_by           BIGINT FK NULL
├── approved_at           TIMESTAMP NULL
├── created_at            TIMESTAMP
├── updated_at            TIMESTAMP
└── deleted_at            TIMESTAMP NULL      -- soft delete
```

---

## 3. جداول الربط

```sql
person_aliases       (person_id, alias, alias_type, source, notes)
person_addresses     (person_id, address_type, governorate, district, street, ...)
person_media         (person_id, media_type, file_path, captured_at, is_primary, ...)
person_convictions   (person_id, charge_id, verdict_date, sentence, court, notes)
person_vehicles      (person_id, vehicle_type, make_model, color, plate_number, ...)
person_associates    (person_id, associate_person_id, relationship_type, confidence, ...)
person_weapons       (person_id, weapon_id, relationship, linked_at, source, notes)
person_type_history  (person_id, from_type, to_type, reason, changed_by, changed_at)

report_persons       (report_id, person_id, role_in_incident, link_source, confidence_score, notes)
report_weapons       (report_id, weapon_id, link_source, confidence_score, notes)
```

---

## 4. الفهارس المقترحة

```sql
-- بحث سريع
INDEX idx_persons_full_name       ON persons(full_name)
INDEX idx_persons_national_id     ON persons(national_id)
INDEX idx_persons_person_type     ON persons(person_type)
INDEX idx_persons_status          ON persons(status)
INDEX idx_persons_risk_level      ON persons(risk_level)
INDEX idx_persons_nationality     ON persons(nationality)

INDEX idx_aliases_alias           ON person_aliases(alias)
INDEX idx_addresses_governorate   ON person_addresses(governorate, district)
INDEX idx_reports_occurred_at     ON reports(occurred_at)
INDEX idx_reports_crime_method    ON reports(crime_method_id)
INDEX idx_reports_location        ON reports(governorate, district)
```

---

## 5. Eloquent Models

```
App\Models\Person
App\Models\PersonAlias
App\Models\PersonAddress
App\Models\PersonMedia
App\Models\PersonConviction
App\Models\PersonVehicle
App\Models\PersonAssociate
App\Models\PersonTypeHistory
App\Models\Report
App\Models\Weapon
App\Models\CrimeType
App\Models\CrimeMethod
App\Models\ChargeType
```

### Person Model — علاقات رئيسية

```php
class Person extends Model
{
    use SoftDeletes;

    // HasMany
    aliases(), addresses(), media(), convictions(),
    vehicles(), typeHistory()

    // BelongsToMany
    associates(), weapons(), reports()

    // BelongsTo
    primaryCharge(), originReport(), createdBy(), approvedBy()

    // Scopes
    scopeVisitors(), scopeRegisteredA(), scopeRegisteredB()
    scopeActive(), scopeWanted(), scopeByRiskLevel()
}
```

---

## 6. Enums (PHP 8.2)

```php
enum PersonType: string {
    case Visitor = 'visitor';
    case RegisteredA = 'registered_a';
    case RegisteredB = 'registered_b';
}

enum PersonStatus: string {
    case Draft = 'draft';
    case Active = 'active';
    case Wanted = 'wanted';
    case Detained = 'detained';
    case Archived = 'archived';
}

enum RiskLevel: string {
    case Low = 'low';
    case Medium = 'medium';
    case High = 'high';
    case Critical = 'critical';
}
```
