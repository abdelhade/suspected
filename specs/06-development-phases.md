# 06 — خطة التنفيذ على مراحل

## المرحلة 0 — التأسيس (3–5 أيام)

### المهام

- [ ] تثبيت `spatie/laravel-permission`
- [ ] تثبيت `spatie/laravel-activitylog`
- [ ] تثبيت `filament/filament` (لوحة الإدارة)
- [ ] إعداد الأدوار: admin, moderator, user
- [ ] Seed للمستخدمين التجريبيين
- [ ] إعداد MySQL وتحديث `.env`
- [ ] دعم RTL للعربية في Filament

### المخرجات

- نظام دخول يعمل
- 3 أدوار بصلاحيات أساسية
- لوحة Filament فارغة جاهزة

---

## المرحلة 1 — سجل المسجّل الأساسي (أسبوع 1–2)

### المهام

- [ ] Migration: `persons` + الحقول الأساسية
- [ ] Migration: `person_aliases`, `person_addresses`, `person_photos`
- [ ] Enum `RegistrationType`: visitor, registered_a, registered_b
- [ ] Model `Person` + Relations + Scopes
- [ ] Filament Resource: `PersonResource`
  - نموذج إنشاء/تعديل حسب النوع (حقول ديناميكية)
  - تبويبات: الهوية، المظهر، جنائي، وسائط
  - Badge للنوع (زائر / A / B)
- [ ] Validation حسب النوع (Form Request)
- [ ] ترقيم تلقائي: V-/A-/B-
- [ ] بحث أساسي: اسم، جواز، رقم قومي، نوع
- [ ] Soft delete + Activity log

### معيار القبول

- إنشاء زائر بحقول محدودة ✅
- إنشاء مسجل A بحقول كاملة ✅
- إنشاء مسجل B ✅
- البحث يعمل ✅
- كل تعديل يُسجَّل في audit ✅

---

## المرحلة 2 — البيانات الغنية (أسبوع 3)

### المهام

- [ ] Migration: `person_charges`, `person_associates`, `person_documents`
- [ ] Migration: `person_phones`, `person_social_accounts`, `person_activity_areas`
- [ ] رفع الصور (Spatie Media Library أو تخزين مباشر)
- [ ] Relation Manager في Filament: التهم، العلاقات، العناوين
- [ ] Workflow اعتماد: draft → active (is_published)
- [ ] ترقية النوع: زائر → B → A
- [ ] صفحة عرض كاملة للمسجّل (Profile View)
- [ ] قيود العرض حسب الدور

### معيار القبول

- ملف شخص كامل بكل التبويبات ✅
- اعتماد من Moderator ✅
- User يرى نسخة محدودة ✅

---

## المرحلة 3 — المحاضر والاقتراحات (أسبوع 4–5)

### المهام

- [ ] Migration: `crime_types`, `crime_methods`, `weapons`
- [ ] Migration: `reports`, `report_persons`, `report_weapons`
- [ ] Seed: أنواع جرائم وأساليب وأسلحة شائعة
- [ ] `ReportSuggestionService`
  - `suggestPersons(criteria)`
  - `suggestWeapons(criteria)`
- [ ] API: `POST /api/reports/suggestions`
- [ ] Filament: `ReportResource` مع Live Suggestions
  - عند تغيير أسلوب/مكان/زمان → تحديث الاقتراحات
  - اختيار شخص/سلاح مقترح أو إضافة يدوي
- [ ] Workflow: draft → pending_review → approved
- [ ] ربط المحضر المعتمد بسجل الشخص (incidents)

### معيار القبول

- كتابة محضر واقتراح أشخاص يعمل ✅
- اقتراح أسلحة يعمل ✅
- اعتماد المحضر يُغذّي الاقتراحات لاحقاً ✅

---

## المرحلة 4 — التحليل والتقارير (أسبوع 6)

### المهام

- [ ] Dashboard في Filament:
  - إجمالي حسب النوع (زائر / A / B)
  - حسب مستوى الخطورة
  - محاضر بانتظار المراجعة
  - آخر النشاط
- [ ] بحث متقدم (فلاتر متعددة)
- [ ] خريطة مناطق النشاط (اختياري — Leaflet)
- [ ] تصدير PDF/Excel محكوم (Admin فقط)
- [ ] تقرير: أشخاص مرتبطون بمحضر معين

### معيار القبول

- Dashboard يعرض إحصائيات حقيقية ✅
- بحث متقدم بـ 5+ فلاتر ✅

---

## المرحلة 5 — التقوية والأمان (أسبوع 7)

### المهام

- [ ] Session timeout (30 دقيقة)
- [ ] Rate limiting على البحث
- [ ] تشفير `national_id` و `passport_number` (encrypted cast)
- [ ] Watermark على الصور عند العرض
- [ ] 2FA لـ Admin و Moderator (اختياري)
- [ ] نسخ احتياطي يومي
- [ ] اختبارات Feature للمسارات الحرجة

---

## الجدول الزمني الملخص

```
الأسبوع 1-2   ████████░░  المرحلة 0 + 1  (أساس السجل)
الأسبوع 3     ██████░░░░  المرحلة 2      (بيانات غنية)
الأسبوع 4-5   ████████░░  المرحلة 3      (محاضر + اقتراحات)
الأسبوع 6     ██████░░░░  المرحلة 4      (تحليل)
الأسبوع 7     ████░░░░░░  المرحلة 5      (أمان)
```

**الإجمالي التقديري: 6–7 أسابيع**

---

## الحزم النهائية

```json
{
  "require": {
    "filament/filament": "^3.0",
    "spatie/laravel-permission": "^6.0",
    "spatie/laravel-activitylog": "^4.0",
    "spatie/laravel-medialibrary": "^11.0"
  },
  "require-dev": {
    "maatwebsite/excel": "^3.1"
  }
}
```

---

## الخطوة التالية المقترحة

ابدأ **المرحلة 0** فوراً:

```bash
composer require filament/filament spatie/laravel-permission spatie/laravel-activitylog
php artisan filament:install --panels
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
```

ثم قل **"ابدأ المرحلة 0"** وسأنفّذ.
