# 07 — خطة التنفيذ على مراحل

## المرحلة 1 — الأساس (أسبوع 1–2)

### الهدف
Auth + أدوار + جدول persons أساسي + Filament

### المهام

- [ ] تثبيت Filament + Spatie Permission + Activity Log
- [ ] إعداد الأدوار: admin, moderator, user
- [ ] Migration: `persons` (الحقول الأساسية — القسم 1 + 7 + 15)
- [ ] Migration: `person_type_history`
- [ ] Enums: PersonType, PersonStatus, RiskLevel
- [ ] Model Person + Scopes
- [ ] Filament Resource: Person (CRUD أساسي)
- [ ] تمييز بصري للنوع (زائر / A / B)
- [ ] بحث بالاسم + رقم الملف
- [ ] Audit log على العرض والتعديل

### معيار القبول
- Admin ينشئ مسجّل A/B
- User ينشئ زائر فقط
- Moderator يعتمد ويرقّي زائر → B

---

## المرحلة 2 — الملف الكامل (أسبوع 3–4)

### الهدف
كل حقول الوصف + جداول فرعية

### المهام

- [ ] Migrations: aliases, addresses, media, convictions, vehicles
- [ ] توسيع Filament Person Resource بـ Tabs لكل قسم
- [ ] رفع صور (Media Library)
- [ ] حقول مشفّرة (national_id, phones)
- [ ] إخفاء حقول 👁 حسب الدور
- [ ] Migrations: crime_types, crime_methods, charge_types, weapons
- [ ] Seeders للقوائم المرجعية

### معيار القبول
- ملف مسجّل A كامل بكل الأقسام الـ 15
- User لا يرى internal_notes و intel_notes

---

## المرحلة 3 — المحاضر والاقتراحات (أسبوع 5–6)

### الهدف
كتابة محضر + اقتراح أشخاص وأسلحة

### المهام

- [ ] Migration: reports, report_persons, report_weapons
- [ ] ReportSuggestionService (معادلة التقييم)
- [ ] API: POST /api/reports/suggestions
- [ ] Filament: Report Resource مع Livewire للاقتراحات الحية
- [ ] ربط شخص بمحضر (suggested / manual)
- [ ] إنشاء زائر من داخل المحضر
- [ ] person_associates + person_weapons

### معيار القبول
- إدخال أسلوب + مكان + زمان → اقتراحات تظهر خلال 1 ثانية
- قبول اقتراح يربط الشخص بالمحضر
- محضر جديد يُغذّي اقتراحات لاحقة

---

## المرحلة 4 — البحث والتحليل (أسبوع 7–8)

### الهدف
بحث متقدم + Dashboard

### المهام

- [ ] بحث متقدم (اسم، هوية، منطقة، تهمة، نوع، خطورة)
- [ ] Laravel Scout + Meilisearch
- [ ] Dashboard: إحصائيات حسب النوع / الخطورة / المنطقة
- [ ] محاضر بانتظار الاعتماد
- [ ] تنبيه: مسجّل A مطابق لمحضر جديد
- [ ] Timeline لكل مسجّل (حوادث + تعديلات)

### معيار القبول
- بحث "أحمد + المعادي + مسجّل A" يعيد نتائج دقيقة < 2 ثانية

---

## المرحلة 5 — التقوية (أسبوع 9+)

### المهام

- [ ] 2FA للـ Admin و Moderator
- [ ] Rate limiting على البحث والتصدير
- [ ] تصدير Excel محكوم (Admin فقط)
- [ ] نسخ احتياطي مجدول
- [ ] Watermark على الصور
- [ ] تحسين أداء الاقتراحات (caching)
- [ ] اختبارات Feature للمسارات الحرجة

---

## ملخص الجدول الزمني

```
أسبوع 1-2  ████████░░  المرحلة 1 — أساس
أسبوع 3-4  ████████░░  المرحلة 2 — ملف كامل
أسبوع 5-6  ████████░░  المرحلة 3 — محاضر + اقتراح
أسبوع 7-8  ████████░░  المرحلة 4 — بحث + Dashboard
أسبوع 9+   ████████░░  المرحلة 5 — أمان وتقوية
```

---

## البدء التالي

عند الموافقة، نبدأ **المرحلة 1**:

```bash
composer require filament/filament spatie/laravel-permission spatie/laravel-activitylog
php artisan filament:install
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
```

ثم أول Migration: `create_persons_table`
