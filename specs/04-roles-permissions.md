# 04 — الأدوار والصلاحيات

## 4.1 الأدوار

| الدور | الكود | الوصف |
|-------|-------|-------|
| مدير النظام | `admin` | صلاحيات كاملة |
| مشرف | `moderator` | إدارة السجلات والمحاضر |
| مستخدم | `user` | بحث وعرض وتقديم بلاغات |

## 4.2 مصفوفة الصلاحيات

### إدارة المستخدمين

| الصلاحية | Admin | Moderator | User |
|----------|:-----:|:---------:|:----:|
| `users.manage` | ✅ | ❌ | ❌ |
| `users.view` | ✅ | ❌ | ❌ |

### سجلات الأشخاص (Persons)

| الصلاحية | Admin | Moderator | User |
|----------|:-----:|:---------:|:----:|
| `persons.view` | ✅ | ✅ | ✅ |
| `persons.view_full` | ✅ | ✅ | ⚠️ محدود |
| `persons.create` | ✅ | ✅ | ❌ |
| `persons.edit` | ✅ | ✅ | ❌ |
| `persons.delete` | ✅ | ⚠️ soft only | ❌ |
| `persons.approve` | ✅ | ✅ | ❌ |
| `persons.approve_type_a` | ✅ | ✅ | ❌ |
| `persons.change_type` | ✅ | ✅ | ❌ |
| `persons.export` | ✅ | ⚠️ | ❌ |
| `persons.view_internal_notes` | ✅ | ✅ | ❌ |
| `persons.view_photos` | ✅ | ✅ | ⚠️ |

### أنواع التسجيل — من يمكنه إنشاء ماذا

| النوع | Admin | Moderator | User |
|-------|:-----:|:---------:|:----:|
| زائر | ✅ | ✅ | ❌ |
| مسجل A | ✅ | ✅ | ❌ |
| مسجل B | ✅ | ✅ | ❌ |

### المحاضر (Reports)

| الصلاحية | Admin | Moderator | User |
|----------|:-----:|:---------:|:----:|
| `reports.view` | ✅ | ✅ | ✅ |
| `reports.create` | ✅ | ✅ | ✅ |
| `reports.edit_own` | ✅ | ✅ | ✅ |
| `reports.edit_any` | ✅ | ✅ | ❌ |
| `reports.approve` | ✅ | ✅ | ❌ |
| `reports.view_suggestions` | ✅ | ✅ | ✅ |
| `reports.confirm_suggestions` | ✅ | ✅ | ⚠️ own only |

### الأسلحة

| الصلاحية | Admin | Moderator | User |
|----------|:-----:|:---------:|:----:|
| `weapons.view` | ✅ | ✅ | ✅ |
| `weapons.manage` | ✅ | ✅ | ❌ |

### النظام

| الصلاحية | Admin | Moderator | User |
|----------|:-----:|:---------:|:----:|
| `audit.view` | ✅ | ✅ | ❌ |
| `settings.manage` | ✅ | ❌ | ❌ |
| `lookups.manage` | ✅ | ✅ | ❌ |

## 4.3 قيود العرض لـ User

عند عرض سجل شخص، المستخدم العادي **لا يرى**:

- `internal_notes`
- `warrant_number` / تفاصيل مذكرة التوقيف
- `source_of_information`
- صور بعلامة `restricted`
- بيانات الاتصال (هاتف، إيميل)
- سجل التعديلات الكامل

## 4.4 Audit Log — ما يُسجَّل

| الحدث | البيانات المسجلة |
|-------|-----------------|
| عرض سجل | user_id, person_id, ip, timestamp |
| بحث | user_id, query_params, results_count |
| إنشاء / تعديل | user_id, model, changes (before/after) |
| اعتماد | user_id, model_id, action |
| تصدير | user_id, filters, record_count |
| دخول / خروج | user_id, ip, user_agent |

## 4.5 الحزمة المقترحة

```
spatie/laravel-permission
```

### أدوار Spatie

```php
Role::create(['name' => 'admin']);
Role::create(['name' => 'moderator']);
Role::create(['name' => 'user']);
```
