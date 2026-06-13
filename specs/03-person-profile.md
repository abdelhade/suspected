# 03 — الوصف الكامل للمسجّل

> هذا الملف يحدد **كل حقل** متاح في ملف الشخص.  
> الحقول مُقسّمة إلى أقسام. الرموز:  
> `●` إلزامي | `○` اختياري | `🔒` حساس (تشفير) | `👁` Admin/Moderator فقط

**ينطبق على:** مسجّل A ومسجّل B بالكامل — زائر يأخذ الحقول المحددة في [02-person-types.md](02-person-types.md).

---

## القسم 1 — التعريف الأساسي

| # | الحقل | المفتاح | النوع | إلزامي | ملاحظات |
|---|-------|---------|-------|:------:|---------|
| 1.1 | رقم الملف | `file_number` | string | ● | تلقائي — فريد — مثال: `REG-2026-00042` |
| 1.2 | نوع المسجّل | `person_type` | enum | ● | `visitor` \| `registered_a` \| `registered_b` |
| 1.3 | الحالة | `status` | enum | ● | `draft` \| `active` \| `wanted` \| `detained` \| `archived` |
| 1.4 | الاسم الكامل | `full_name` | string | ● | كما في الهوية أو الأكثر شيوعاً |
| 1.5 | الاسم بالإنجليزية | `full_name_en` | string | ○ | للأجانب |
| 1.6 | اللقب / الكنية | `nickname` | string | ○ | |
| 1.7 | الجنس | `gender` | enum | ● | `male` \| `female` \| `unknown` |
| 1.8 | تاريخ الميلاد | `birth_date` | date | ○ | |
| 1.9 | العمر التقريبي | `approximate_age` | int | ○ | عند عدم معرفة التاريخ |
| 1.10 | مكان الميلاد | `birth_place` | string | ○ | محافظة / دولة |
| 1.11 | الجنسية | `nationality` | string | ● | |
| 1.12 | جنسية ثانية | `second_nationality` | string | ○ | |
| 1.13 | الديانة | `religion` | string | ○ | |
| 1.14 | الحالة الاجتماعية | `marital_status` | enum | ○ | `single` \| `married` \| `divorced` \| `widowed` \| `unknown` |
| 1.15 | عدد الأبناء | `children_count` | int | ○ | |

---

## القسم 2 — أسماء مستعارة ومعروف بـ (Aliases)

جدول منفصل: `person_aliases`

| # | الحقل | المفتاح | النوع | إلزامي | ملاحظات |
|---|-------|-------|---------|:------:|---------|
| 2.1 | الاسم المستعار | `alias` | string | ● | |
| 2.2 | نوع الاسم | `alias_type` | enum | ○ | `nickname` \| `fake_name` \| `gang_name` \| `other` |
| 2.3 | مصدر الاسم | `source` | string | ○ | محضر رقم... / شاهد |
| 2.4 | ملاحظات | `notes` | text | ○ | |

---

## القسم 3 — الوثائق والهوية

| # | الحقل | المفتاح | النوع | إلزامي | ملاحظات |
|---|-------|---------|-------|:------:|---------|
| 3.1 | الرقم القومي | `national_id` | string | ○ | 🔒 |
| 3.2 | رقم جواز السفر | `passport_number` | string | ○ | 🔒 |
| 3.3 | دولة إصدار الجواز | `passport_country` | string | ○ | |
| 3.4 | تاريخ انتهاء الجواز | `passport_expiry` | date | ○ | |
| 3.5 | رقم الإقامة | `residency_number` | string | ○ | 🔒 — للأجانب |
| 3.6 | نوع الإقامة | `residency_type` | enum | ○ | `tourist` \| `work` \| `student` \| `permanent` \| `illegal` |
| 3.7 | رخصة القيادة | `driving_license` | string | ○ | 🔒 |
| 3.8 | رقم قيد عسكري | `military_id` | string | ○ | 🔒 |
| 3.9 | بطاقة ضريبية / سجل تجاري | `tax_id` | string | ○ | 🔒 |

---

## القسم 4 — الوصف الجسدي

| # | الحقل | المفتاح | النوع | إلزامي | ملاحظات |
|---|-------|---------|-------|:------:|---------|
| 4.1 | الطول (سم) | `height_cm` | int | ○ | |
| 4.2 | الوزن (كجم) | `weight_kg` | int | ○ | |
| 4.3 | لون البشرة | `skin_color` | enum | ○ | `light` \| `medium` \| `dark` \| `very_dark` |
| 4.4 | لون الشعر | `hair_color` | string | ○ | |
| 4.5 | نوع الشعر | `hair_type` | enum | ○ | `straight` \| `curly` \| `bald` \| `wavy` |
| 4.6 | لون العينين | `eye_color` | string | ○ | |
| 4.7 | شكل الوجه | `face_shape` | string | ○ | طويل / عريض / بيضاوي... |
| 4.8 | لحية / شارب | `facial_hair` | string | ○ | |
| 4.9 | علامات مميزة | `distinguishing_marks` | text | ○ | ندبة، وشم، عيب خلقي... |
| 4.10 | وصف الملابس المعتادة | `usual_clothing` | text | ○ | |
| 4.11 | طريقة المشي | `gait_description` | string | ○ | عرج، بطء... |
| 4.12 | لهجة / لكنة | `accent` | string | ○ | صعيدي، سوري... |
| 4.13 | الوصف الجسدي الحر | `physical_description` | text | ○ | نص شامل — **إلزامي للزائر** |
| 4.14 | إعاقة جسدية | `disability` | string | ○ | |

---

## القسم 5 — التواصل والعناوين

### 5.1 عناوين — جدول `person_addresses`

| # | الحقل | المفتاح | النوع | إلزامي | ملاحظات |
|---|-------|---------|-------|:------:|---------|
| 5.1 | نوع العنوان | `address_type` | enum | ● | `current` \| `previous` \| `work` \| `hideout` \| `relative` |
| 5.2 | المحافظة | `governorate` | string | ● | |
| 5.3 | المدينة / الحي | `district` | string | ○ | |
| 5.4 | الشارع / تفاصيل | `street` | text | ○ | 🔒 |
| 5.5 | رقم المبنى | `building` | string | ○ | |
| 5.6 | خط العرض | `latitude` | decimal | ○ | |
| 5.7 | خط الطول | `longitude` | decimal | ○ | |
| 5.8 | منذ تاريخ | `valid_from` | date | ○ | |
| 5.9 | حتى تاريخ | `valid_until` | date | ○ | |
| 5.10 | ملاحظات | `notes` | text | ○ | |

### 5.2 اتصال

| # | الحقل | المفتاح | النوع | إلزامي | ملاحظات |
|---|-------|---------|-------|:------:|---------|
| 5.11 | هاتف 1 | `phone_1` | string | ○ | 🔒 |
| 5.12 | هاتف 2 | `phone_2` | string | ○ | 🔒 |
| 5.13 | واتساب / تيليجرام | `messaging_id` | string | ○ | 🔒 |
| 5.14 | بريد إلكتروني | `email` | string | ○ | 🔒 |
| 5.15 | حسابات تواصل | `social_accounts` | json | ○ | 🔒 — `{platform, username}` |

---

## القسم 6 — العمل والتعليم

| # | الحقل | المفتاح | النوع | إلزامي | ملاحظات |
|---|-------|---------|-------|:------:|---------|
| 6.1 | المستوى التعليمي | `education_level` | enum | ○ | `none` \| `primary` \| `secondary` \| `university` \| `postgraduate` |
| 6.2 | التخصص | `education_field` | string | ○ | |
| 6.3 | المهنة | `occupation` | string | ○ | |
| 6.4 | جهة العمل | `employer` | string | ○ | |
| 6.5 | عنوان العمل | `work_address` | text | ○ | |
| 6.6 | الدخل التقريبي | `income_range` | enum | ○ | `low` \| `medium` \| `high` \| `unknown` |
| 6.7 | مصدر الدخل | `income_source` | string | ○ | رسمي / غير مشروع... |

---

## القسم 7 — التصنيف والخطورة

| # | الحقل | المفتاح | النوع | إلزامي | ملاحظات |
|---|-------|---------|-------|:------:|---------|
| 7.1 | مستوى الخطورة | `risk_level` | enum | ● | `low` \| `medium` \| `high` \| `critical` |
| 7.2 | تصنيف التهمة الرئيسية | `primary_charge_id` | FK | ○ | → `charge_types` |
| 7.3 | تصنيفات فرعية | `charge_tags` | json/array | ○ | تهمة إضافية |
| 7.4 | انتماء جماعي | `gang_affiliation` | string | ○ | عصابة / تنظيم |
| 7.5 | دور في الجماعة | `gang_role` | string | ○ | قائد / عضو... |
| 7.6 | متعاطي مواد | `substance_involvement` | enum | ○ | `none` \| `user` \| `dealer` \| `unknown` |
| 7.7 | يحمل سلاحاً بانتظام | `armed_habitually` | boolean | ○ | |
| 7.8 | عنيف / خطر على المجندين | `violent_tendency` | enum | ○ | `low` \| `medium` \| `high` |
| 7.9 | ملاحظات خطورة | `risk_notes` | text | ○ | 👁 |

---

## القسم 8 — الحالة القانونية

| # | الحقل | المفتاح | النوع | إلزامي | ملاحظات |
|---|-------|---------|-------|:------:|---------|
| 8.1 | مطلوب قضائياً | `is_wanted` | boolean | ● | |
| 8.2 | رقم مذكرة التوقيف | `warrant_number` | string | ○ | |
| 8.3 | تاريخ المذكرة | `warrant_date` | date | ○ | |
| 8.4 | جهة الإصدار | `warrant_issuer` | string | ○ | نيابة / قسم... |
| 8.5 | موقوف حالياً | `is_detained` | boolean | ○ | |
| 8.6 | مكان الاحتجاز | `detention_place` | string | ○ | 👁 |
| 8.7 | تاريخ بداية الاحتجاز | `detention_start` | date | ○ | |
| 8.8 | سوابق جنائية | `criminal_record_summary` | text | ○ | ملخص — التفاصيل في جدول منفصل |
| 8.9 | عدد السوابق | `prior_convictions_count` | int | ○ | |
| 8.10 | تحت رقابة | `under_surveillance` | boolean | ○ | |
| 8.11 | تاريخ بداية الرقابة | `surveillance_start` | date | ○ | |

### جدول السوابق: `person_convictions`

| الحقل | النوع | ملاحظات |
|-------|-------|---------|
| charge_id | FK | التهمة |
| verdict_date | date | تاريخ الحكم |
| sentence | string | العقوبة |
| court | string | المحكمة |
| notes | text | |

---

## القسم 9 — الصور والوسائط

جدول: `person_media`

| # | الحقل | المفتاح | النوع | إلزامي | ملاحظات |
|---|-------|---------|-------|:------:|---------|
| 9.1 | نوع الوسائط | `media_type` | enum | ● | `face` \| `full_body` \| `mark` \| `document` \| `cctv` \| `other` |
| 9.2 | الملف | `file_path` | string | ● | تخزين مشفّر |
| 9.3 | تاريخ الالتقاط | `captured_at` | date | ○ | |
| 9.4 | مصدر الصورة | `source` | string | ○ | كاميرا / محضر / شاهد |
| 9.5 | وصف | `description` | text | ○ | |
| 9.6 | الصورة الرئيسية | `is_primary` | boolean | ○ | واحدة فقط |

**حدود:**
- زائر: 2 صورة كحد أقصى
- مسجّل A/B: غير محدود (عملياً 20)

---

## القسم 10 — الأسلحة المرتبطة

جدول ربط: `person_weapons`

| # | الحقل | المفتاح | النوع | ملاحظات |
|---|-------|---------|-------|---------|
| 10.1 | weapon_id | FK | → `weapons` | |
| 10.2 | علاقة | `relationship` | enum | `owns` \| `used` \| `suspected` |
| 10.3 | تاريخ الربط | `linked_at` | date | |
| 10.4 | مصدر | `source` | string | محضر رقم... |
| 10.5 | ملاحظات | `notes` | text | |

> **غير متاح للزائر** — يُضاف بعد الترقية لـ A/B

---

## القسم 11 — العلاقات والشركاء

جدول: `person_associates`

| # | الحقل | المفتاح | النوع | ملاحظات |
|---|-------|---------|-------|---------|
| 11.1 | associate_person_id | FK | → `persons` | |
| 11.2 | نوع العلاقة | `relationship_type` | enum | `partner` \| `family` \| `gang_member` \| `informant` \| `victim` \| `other` |
| 11.3 | وصف العلاقة | `description` | text | |
| 11.4 | موثوقية الرابط | `confidence` | enum | `low` \| `medium` \| `high` |
| 11.5 | مصدر | `source` | string | |

---

## القسم 12 — المركبات

جدول: `person_vehicles`

| # | الحقل | المفتاح | النوع | ملاحظات |
|---|-------|---------|-------|---------|
| 12.1 | نوع المركبة | `vehicle_type` | enum | `car` \| `motorcycle` \| `truck` \| `other` |
| 12.2 | الماركة / الموديل | `make_model` | string | |
| 12.3 | اللون | `color` | string | |
| 12.4 | رقم اللوحة | `plate_number` | string | 🔒 |
| 12.5 | رقم الشاسيه | `chassis_number` | string | 🔒 |
| 12.6 | ملاحظات | `notes` | text | |

---

## القسم 13 — المحاضر والحوادث المرتبطة

جدول ربط: `report_persons` (من المحضر)

| # | الحقل | المفتاح | النوع | ملاحظات |
|---|-------|---------|-------|---------|
| 13.1 | report_id | FK | → `reports` | |
| 13.2 | دور في الحادث | `role_in_incident` | enum | `perpetrator` \| `suspect` \| `witness` \| `victim` \| `accomplice` |
| 13.3 | مصدر الربط | `link_source` | enum | `suggested` \| `manual` \| `confirmed` |
| 13.4 | نسبة الثقة | `confidence_score` | int | 0–100 — للاقتراحات الآلية |
| 13.5 | ملاحظات | `notes` | text | |

---

## القسم 14 — ملاحظات ووصف حر

| # | الحقل | المفتاح | النوع | إلزامي | ملاحظات |
|---|-------|---------|-------|:------:|---------|
| 14.1 | ملاحظات عامة | `general_notes` | text | ○ | |
| 14.2 | ملاحظات داخلية | `internal_notes` | text | ○ | 👁 — لا يراها User |
| 14.3 | سلوكيات معروفة | `known_behaviors` | text | ○ | يعمل ليلاً، يرتدي كذا... |
| 14.4 | أماكن تردد | `frequent_locations` | text | ○ | |
| 14.5 | وسائل هروب معروفة | `escape_methods` | text | ○ | |
| 14.6 | معلومات استخباراتية | `intel_notes` | text | ○ | 👁🔒 |

---

## القسم 15 — بيانات إدارية (تلقائية)

| # | الحقل | المفتاح | النوع | ملاحظات |
|---|-------|---------|-------|---------|
| 15.1 | أنشئ بواسطة | `created_by` | FK users | |
| 15.2 | تاريخ الإنشاء | `created_at` | timestamp | |
| 15.3 | عُدّل بواسطة | `updated_by` | FK users | |
| 15.4 | تاريخ التعديل | `updated_at` | timestamp | |
| 15.5 | اعتمده | `approved_by` | FK users | nullable |
| 15.6 | تاريخ الاعتماد | `approved_at` | timestamp | nullable |
| 15.7 | منشأ من محضر | `origin_report_id` | FK | إن وُجد — للزائر خاصة |
| 15.8 | تاريخ انتهاء الصلاحية | `expires_at` | date | للزائر فقط |
| 15.9 | محذوف (soft) | `deleted_at` | timestamp | |

---

## ملخص الأقسام

```
┌─────────────────────────────────────────────┐
│ 1. تعريف أساسي          (15 حقل)           │
│ 2. أسماء مستعارة        (جدول)             │
│ 3. وثائق وهوية          (9 حقول)           │
│ 4. وصف جسدي             (14 حقل)           │
│ 5. عناوين واتصال        (جدول + 5 حقول)    │
│ 6. عمل وتعليم           (7 حقول)           │
│ 7. تصنيف وخطورة         (9 حقول)           │
│ 8. حالة قانونية         (11 + جدول سوابق)  │
│ 9. صور ووسائط           (جدول)             │
│ 10. أسلحة               (جدول ربط)         │
│ 11. علاقات              (جدول)             │
│ 12. مركبات              (جدول)             │
│ 13. محاضر               (جدول ربط)         │
│ 14. ملاحظات حرة         (6 حقول)           │
│ 15. بيانات إدارية       (9 حقول)           │
└─────────────────────────────────────────────┘
```

**إجمالي تقريبي:** ~85+ حقل / علاقة across الجداول
