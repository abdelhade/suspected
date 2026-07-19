# TODO

## الهدف
ربط المحاضر (Reports) اللي حالتها pending بسجل التدقيق (Audit Log) وظهورها في صفحة سجل التدقيق.

## الخطوات
1. تحديث منطق المحاضر في `ReportController`:
   - عند إنشاء محضر جديد => تسجيل حدث `create` مرتبط بـ `auditable_type=Report` و `auditable_id`.
   - عند تحديث المحضر => تسجيل حدث `update`.
   - تحديدًا عند تغيّر `current_status` إلى `pending` => تسجيل حدث `update` (مع old/new values) بحيث يظهر ضمن سجل التدقيق.
2. (مهم) التأكد أن `AuditLog::EVENT_LABELS` بيحتوي على event اللي سجلناه (create/update/delete) — موجودة بالفعل.

3. التأكد أن `AuditLog::AUDITABLE_LABELS` فيها `Report` (واضح إنها موجودة بالفعل).
4. تشغيل migrate / اختبار يدوي:
   - إنشاء محضر new
   - تعديل محضر وتغيير `current_status` إلى pending
   - فتح `/audit-log` والتأكد ظهور الحدث مع الوصف ومعرّف المحضر.

