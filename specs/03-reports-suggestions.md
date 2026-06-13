# 03 — المحاضر واقتراح الأشخاص والأسلحة

## 3.1 تعريف المحضر

المحضر (`reports`) هو سجل حادث يربط بين:
- **أسلوب الجريمة** + **المكان** + **الزمان**
- الأشخاص المشتبه بهم (مقترحون أو مؤكدون)
- الأسلحة المستخدمة (مقترحة أو مؤكدة)

## 3.2 حقول المحضر

| الحقل | النوع | إلزامي | ملاحظات |
|-------|-------|:------:|---------|
| `report_number` | string | ● | رقم تلقائي: `R-2026-00001` |
| `crime_type_id` | FK | ● | نوع الجريمة (سرقة، قتل، ...) |
| `crime_method_id` | FK | ● | أسلوب التنفيذ (مسلح، إكراه، ...) |
| `occurred_at` | datetime | ● | تاريخ ووقت الحادث |
| `governorate` | string | ● | المحافظة |
| `district` | string | ○ | الحي / المركز |
| `location_text` | text | ○ | وصف تفصيلي للمكان |
| `latitude` | decimal | ○ | |
| `longitude` | decimal | ○ | |
| `description` | text | ● | تفاصيل المحضر |
| `status` | enum | ● | draft / pending_review / approved / archived |
| `created_by` | user_id | ● | |
| `reviewed_by` | user_id | ○ | |
| `approved_by` | user_id | ○ | |

## 3.3 ربط المحضر بالأشخاص

جدول `report_persons`:

| الحقل | النوع | ملاحظات |
|-------|-------|---------|
| `report_id` | FK | |
| `person_id` | FK | → `persons` |
| `role` | enum | suspect / witness / victim |
| `source` | enum | `suggested` / `manual` |
| `confidence_score` | int 0-100 | من محرك الاقتراح |
| `suggestion_reason` | text | سبب الاقتراح |
| `is_confirmed` | bool | هل أكّده المحقق |

## 3.4 ربط المحضر بالأسلحة

جدول `report_weapons`:

| الحقل | النوع | ملاحظات |
|-------|-------|---------|
| `report_id` | FK | |
| `weapon_id` | FK | → `weapons` |
| `source` | enum | `suggested` / `manual` |
| `confidence_score` | int | |
| `is_confirmed` | bool | |

## 3.5 محرك الاقتراح (Suggestion Engine)

### المدخلات

```json
{
  "crime_type_id": 2,
  "crime_method_id": 5,
  "occurred_at": "2026-03-14T23:00:00",
  "governorate": "القاهرة",
  "district": "المعادي"
}
```

### المخرجات

```json
{
  "persons": [
    {
      "id": 12,
      "name": "أحمد محمد",
      "registration_type": "registered_a",
      "score": 85,
      "reason": "3 حوادث سرقة مسلحة في المعادي — مسجل A"
    }
  ],
  "weapons": [
    {
      "id": 3,
      "type": "مسدس",
      "caliber": "9mm",
      "score": 78,
      "incidents_count": 4
    }
  ]
}
```

### معادلة التقييم

| العامل | الوزن | التفاصيل |
|--------|-------|----------|
| تطابق أسلوب الجريمة | 35% | `crime_method_id` متطابق |
| قرب المكان | 30% | نفس الحي = 100%، نفس المحافظة = 60%، مجاور = 40% |
| قرب الزمان | 15% | نفس الفترة (صباح/مساء/ليل)، نفس يوم الأسبوع |
| تطابق نوع الجريمة | 10% | `crime_type_id` متطابق |
| تكرار الشخص | 10% | عدد الحوادث السابقة المشابهة |

### وزن إضافي حسب نوع التسجيل

| النوع | مضاعف الدرجة |
|-------|-------------|
| مسجل A | × 1.2 |
| مسجل B | × 1.0 |
| زائر | × 0.7 |

### عتبات العرض

| الدرجة | التصنيف | العرض |
|--------|---------|-------|
| ≥ 70 | قوي | يظهر في أعلى القائمة |
| 40–69 | محتمل | يظهر بدرجة ثانية |
| < 40 | ضعيف | لا يُعرض (إلا بطلب "عرض الكل") |

## 3.6 سير العمل

```
User يكتب محضر (مسودة)
    ↓
النظام يقترح أشخاص + أسلحة (live)
    ↓
User يختار / يضيف يدوياً
    ↓
إرسال للمراجعة (pending_review)
    ↓
Moderator يراجع ويعتمد
    ↓
approved → يُغذّي محرك الاقتراح مستقبلاً
```

## 3.7 API Endpoints

| Method | Path | الوصف |
|--------|------|-------|
| POST | `/api/reports` | إنشاء محضر |
| PUT | `/api/reports/{id}` | تعديل |
| POST | `/api/reports/suggestions` | اقتراحات حية |
| POST | `/api/reports/{id}/confirm-person` | تأكيد شخص مقترح |
| POST | `/api/reports/{id}/confirm-weapon` | تأكيد سلاح مقترح |
