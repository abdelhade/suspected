# 05 — الأدوار والصلاحيات

## 1. الأدوار

```
admin      → مدير النظام
moderator  → مشرف / محقق
user       → مستخدم عادي (ضابط ميداني)
```

---

## 2. مصفوفة الصلاحيات

### المسجّلون (Persons)

| الصلاحية | Admin | Moderator | User |
|----------|:-----:|:---------:|:----:|
| عرض قائمة المسجّلين | ✅ | ✅ | ✅ |
| عرض ملف مسجّل A/B كامل | ✅ | ✅ | ⚠️ بدون حقول 👁 |
| عرض زائر | ✅ | ✅ | ✅ |
| إنشاء زائر | ✅ | ✅ | ✅ (من محضر فقط) |
| إنشاء مسجّل A/B | ✅ | ✅ | ❌ |
| تعديل مسجّل A/B | ✅ | ✅ | ❌ |
| ترقية زائر → A/B | ✅ | ✅ (B فقط) | ❌ |
| ترقية B → A | ✅ | ❌ | ❌ |
| حذف (soft) | ✅ | ⚠️ زائر فقط | ❌ |
| اعتماد المسجّل | ✅ | ✅ | ❌ |
| تصدير بيانات | ✅ | ⚠️ محدود | ❌ |

### المحاضر (Reports)

| الصلاحية | Admin | Moderator | User |
|----------|:-----:|:---------:|:----:|
| إنشاء محضر | ✅ | ✅ | ✅ |
| تعديل محضر | ✅ | ✅ | ⚠️ مسودته فقط |
| اعتماد محضر | ✅ | ✅ | ❌ |
| عرض اقتراحات | ✅ | ✅ | ✅ |
| ربط شخص بمحضر | ✅ | ✅ | ✅ |
| حذف محضر | ✅ | ❌ | ❌ |

### النظام

| الصلاحية | Admin | Moderator | User |
|----------|:-----:|:---------:|:----:|
| إدارة مستخدمين | ✅ | ❌ | ❌ |
| إعدادات النظام | ✅ | ❌ | ❌ |
| سجل التدقيق | ✅ | عرض | ❌ |
| إدارة القوائم المرجعية | ✅ | ⚠️ قراءة | ❌ |
| Dashboard إحصائيات | ✅ | ✅ | ⚠️ محدود |

---

## 3. الحقول المحمية (👁)

لا يراها **User**:

- `internal_notes`
- `intel_notes`
- `detention_place`
- `warrant_number` (عرض فقط "مطلوب: نعم/لا")
- `national_id` (مخفى جزئياً: `***********1234`)

---

## 4. سجل التدقيق `audit_logs`

يُسجَّل تلقائياً:

| الحدث | البيانات |
|-------|----------|
| عرض ملف مسجّل | person_id, user_id, ip, timestamp |
| تعديل حقل | person_id, field, old_value, new_value |
| بحث | query_params, results_count |
| تصدير | scope, record_count |
| تسجيل دخول/خروج | user_id, ip |
| تغيير نوع مسجّل | person_id, from_type, to_type |

**الاحتفاظ:** 5 سنوات minimum

---

## 5. تنفيذ Laravel

```php
// Spatie Permission — أسماء الصلاحيات المقترحة

// Persons
'persons.view'
'persons.view.full'        // Moderator+
'persons.create.visitor'
'persons.create.registered'
'persons.update'
'persons.delete'
'persons.approve'
'persons.promote.to_b'       // Moderator+
'persons.promote.to_a'       // Admin only
'persons.export'

// Reports
'reports.view'
'reports.create'
'reports.update'
'reports.approve'
'reports.delete'

// System
'users.manage'
'settings.manage'
'audit.view'
'lookups.manage'
'dashboard.view'
```
