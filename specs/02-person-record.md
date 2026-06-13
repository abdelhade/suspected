# 02 — سجل المسجّل: الأنواع وكل الأوصاف المتاحة

## 2.1 أنواع التسجيل (Registration Type)

كل شخص في النظام له **نوع تسجيل واحد** يحدد عمق البيانات المطلوبة ومستوى المتابعة.

| النوع | الكود | التعريف | متى يُستخدم |
|-------|-------|---------|-------------|
| **زائر** | `visitor` | شخص أجنبي أو مؤقت؛ بيانات محدودة؛ مراقبة خفيفة | زائر اشتبه في نشاطه، إقامة قصيرة، بلاغ أولي بدون تأكيد |
| **مسجل A** | `registered_a` | تسجيل كامل — **خطورة عالية** أو تأكيد قوي | مطلوب قضائياً، تاريخ إجرامي مؤكد، تكرار في حوادث |
| **مسجل B** | `registered_b` | تسجيل كامل — **خطورة متوسطة/منخفضة** أو مراقبة | اشتباه مؤكد جزئياً، سلوك مشبوه متكرر، شريك مع مسجل A |

### 2.1.1 الفروقات بين الأنواع

| الخاصية | زائر | مسجل A | مسجل B |
|---------|:----:|:------:|:------:|
| الحقول الإلزامية | قليلة (انظر §2.2) | كل الحقول الأساسية | كل الحقول الأساسية |
| ربط بالمحاضر | ✅ | ✅ | ✅ |
| ظهور في اقتراحات النظام | ⚠️ بدرجة أقل | ✅ أولوية عالية | ✅ أولوية متوسطة |
| اعتماد الإدخال | Moderator | Admin أو Moderator | Moderator |
| صلاحية العرض لـ User | محدود | كامل حسب الصلاحية | كامل حسب الصلاحية |
| ترقية النوع | → A أو B بعد التحقق | — | → A عند تصعيد الخطورة |
| انتهاء المتابعة | تاريخ مغادرة / انتهاء إقامة | لا ينتهي إلا بإغلاق القضية | مراجعة دورية |

### 2.1.2 قواعد الترقية والتخفيض

```
زائر  ──[تأكيد خطورة عالية]──►  مسجل A
زائر  ──[تأكيد اشتباه متوسط]──►  مسجل B
مسجل B ──[تصعيد / تكرار حوادث]──►  مسجل A
مسجل A/B ──[إغلاق قضية / براءة]──►  أرشيف (لا حذف)
```

---

## 2.2 الحقول المتاحة — حسب الفئة

> **رموز الإلزامية:**  
> `●` إلزامي لكل الأنواع | `◐` إلزامي لـ A و B فقط | `○` اختياري | `—` غير متاح

---

### 2.2.1 الهوية والتعريف (Identity)

| الحقل | النوع | زائر | A | B | ملاحظات |
|-------|-------|:----:|:-:|:-:|---------|
| `registration_number` | string | ● | ● | ● | رقم تسجيل داخلي تلقائي |
| `registration_type` | enum | ● | ● | ● | visitor / registered_a / registered_b |
| `full_name` | string | ● | ● | ● | الاسم الكامل الرسمي |
| `full_name_ar` | string | ○ | ● | ● | الاسم بالعربية |
| `aliases` | array → جدول منفصل | ○ | ● | ● | أسماء مستعارة، كنى |
| `national_id` | string | ○ | ● | ◐ | الرقم القومي |
| `passport_number` | string | ● | ○ | ○ | إلزامي للزائر |
| `passport_country` | string | ● | ○ | ○ | دولة إصدار الجواز |
| `nationality` | string | ● | ● | ● | الجنسية |
| `second_nationality` | string | ○ | ○ | ○ | جنسية ثانية إن وُجدت |
| `birth_date` | date | ○ | ● | ● | تاريخ الميلاد |
| `birth_place` | string | ○ | ○ | ○ | مكان الميلاد |
| `gender` | enum | ● | ● | ● | male / female |
| `marital_status` | enum | ○ | ○ | ○ | أعزب، متزوج، ... |
| `religion` | string | ○ | ○ | ○ | اختياري |
| `mother_name` | string | ○ | ◐ | ○ | للتأكد من الهوية |
| `registration_date` | date | ● | ● | ● | تاريخ أول تسجيل |
| `visa_type` | string | ○ | — | — | نوع التأشيرة (زائر فقط) |
| `visa_expiry` | date | ○ | — | — | انتهاء التأشيرة |
| `entry_date` | date | ○ | — | — | تاريخ الدخول للبلد |
| `expected_departure` | date | ○ | — | — | تاريخ المغادرة المتوقع |

---

### 2.2.2 الوصف الجسدي والمظهر (Physical Description)

| الحقل | النوع | زائر | A | B | ملاحظات |
|-------|-------|:----:|:-:|:-:|---------|
| `height_cm` | int | ○ | ● | ○ | الطول بالسم |
| `weight_kg` | int | ○ | ○ | ○ | الوزن |
| `build` | enum | ○ | ● | ○ | نحيف، متوسط، ممتلئ، ضخم |
| `skin_color` | string | ○ | ○ | ○ | |
| `eye_color` | string | ○ | ○ | ○ | |
| `hair_color` | string | ○ | ○ | ○ | |
| `hair_style` | string | ○ | ○ | ○ | أصلع، قصير، طويل، ... |
| `facial_hair` | string | ○ | ○ | ○ | لحية، شارب، ... |
| `distinguishing_marks` | text | ○ | ● | ○ | ندوب،وشم، عيوب خلقية |
| `disabilities` | text | ○ | ○ | ○ | إعاقات ظاهرة |
| `accent_dialect` | string | ○ | ○ | ○ | اللهجة أو المنطقة |
| `languages` | array | ○ | ○ | ○ | اللغات المتحدث بها |
| `physical_description_free` | text | ○ | ● | ● | وصف حر نصي شامل |

---

### 2.2.3 الاتصال والعناوين (Contact & Locations)

| الحقل | النوع | زائر | A | B | ملاحظات |
|-------|-------|:----:|:-:|:-:|---------|
| `addresses` | relation | ○ | ● | ● | جدول `person_addresses` — متعدد |
| `address.governorate` | string | ○ | ● | ● | المحافظة |
| `address.district` | string | ○ | ○ | ○ | الحي / المركز |
| `address.street` | string | ○ | ○ | ○ | |
| `address.building` | string | ○ | ○ | ○ | |
| `address.latitude` | decimal | ○ | ○ | ○ | للخريطة |
| `address.longitude` | decimal | ○ | ○ | ○ | |
| `address.type` | enum | ○ | ● | ● | سكن، عمل، مؤقت، معروف سابقاً |
| `address.is_current` | bool | ○ | ● | ● | العنوان الحالي |
| `phone_numbers` | array | ○ | ○ | ○ | جدول `person_phones` |
| `email` | string | ○ | ○ | ○ | |
| `social_accounts` | array | ○ | ○ | ○ | جدول `person_social_accounts` |
| `workplace` | string | ○ | ○ | ○ | جهة العمل |
| `occupation` | string | ○ | ○ | ○ | المهنة |
| `activity_areas` | array | ○ | ● | ○ | مناطق نشاط معروفة (جدول) |
| `hotel_accommodation` | string | ○ | — | — | مكان الإقامة للزائر |

---

### 2.2.4 الوصف الجنائي والسلوكي (Criminal Profile)

| الحقل | النوع | زائر | A | B | ملاحظات |
|-------|-------|:----:|:-:|:-:|---------|
| `risk_level` | enum | ○ | ● | ● | low / medium / high / critical |
| `legal_status` | enum | ○ | ● | ● | wanted / under_surveillance / detained / released / cleared |
| `charges` | relation | ○ | ● | ● | جدول `person_charges` — التهم |
| `charge.charge_type` | string | ○ | ● | ● | نوع التهمة |
| `charge.charge_date` | date | ○ | ○ | ○ | |
| `charge.court` | string | ○ | ○ | ○ | المحكمة |
| `charge.case_number` | string | ○ | ○ | ○ | رقم القضية |
| `charge.status` | enum | ○ | ○ | ○ | قيد النظر / محكوم / مبرأ |
| `crime_methods` | relation | ○ | ● | ● | أساليب الجريمة المعروفة له |
| `crime_types` | relation | ○ | ● | ● | أنواع الجرائم المرتبطة |
| `weapons_used` | relation | ○ | ● | ○ | أسلحة مرتبطة بالشخص |
| `modus_operandi` | text | ○ | ● | ● | **أسلوب عمله** — وصف تفصيلي |
| `criminal_history_summary` | text | ○ | ● | ● | ملخص السجل الجنائي |
| `gang_affiliation` | string | ○ | ○ | ○ | انتماء عصابي |
| `associates` | relation | ○ | ● | ● | علاقات مع مسجلين آخرين |
| `associate.relation_type` | enum | ○ | ○ | ○ | شريك، عميل، قريب، ... |
| `recidivism_count` | int | ○ | ● | ○ | عدد مرات التكرار — محسوب |
| `warrant_number` | string | ○ | ◐ | ○ | رقم مذكرة التوقيف |
| `warrant_date` | date | ○ | ◐ | ○ | |
| `warrant_issuing_authority` | string | ○ | ◐ | ○ | الجهة المصدرة |

---

### 2.2.5 الأنماط الزمنية (Temporal Patterns)

| الحقل | النوع | زائر | A | B | ملاحظات |
|-------|-------|:----:|:-:|:-:|---------|
| `incidents` | relation | ○ | ● | ● | جدول الحوادث/المحاضر المرتبطة |
| `preferred_time_of_day` | enum | ○ | ○ | ○ | morning / afternoon / evening / night |
| `preferred_days` | array | ○ | ○ | ○ | أيام الأسبوع النشطة |
| `seasonal_pattern` | text | ○ | ○ | ○ | ملاحظات موسمية |
| `last_known_activity_at` | datetime | ○ | ● | ● | آخر نشاط مسجل |
| `last_seen_location` | string | ○ | ● | ○ | آخر مكان شوهد فيه |

---

### 2.2.6 الوسائط والمرفقات (Media & Documents)

| الحقل | النوع | زائر | A | B | ملاحظات |
|-------|-------|:----:|:-:|:-:|---------|
| `photos` | relation | ○ | ● | ● | جدول `person_photos` |
| `photo.type` | enum | ○ | ● | ● | face / full_body / mark / document_scan |
| `photo.taken_at` | date | ○ | ○ | ○ | |
| `photo.source` | string | ○ | ○ | ○ | مصدر الصورة |
| `photo.is_primary` | bool | ○ | ● | ● | الصورة الرئيسية |
| `documents` | relation | ○ | ● | ● | مستندات مرفقة |
| `document.type` | enum | ○ | ○ | ○ | id_copy / warrant / court_paper / report |
| `fingerprints` | file/ref | ○ | ◐ | ○ | بصمات — مرجع خارجي |
| `voice_sample` | file | ○ | ○ | ○ | عينة صوت — اختياري |

---

### 2.2.7 التصنيف والحالة الإدارية (Status & Metadata)

| الحقل | النوع | زائر | A | B | ملاحظات |
|-------|-------|:----:|:-:|:-:|---------|
| `status` | enum | ● | ● | ● | draft / active / archived / closed |
| `is_published` | bool | ○ | ● | ● | معتمد ويظهر في البحث |
| `internal_notes` | text | ○ | ● | ● | ملاحظات داخلية للمحققين |
| `public_notes` | text | ○ | ○ | ○ | ملاحظات تظهر في التقارير |
| `tags` | array | ○ | ○ | ○ | وسوم حرة للتصنيف |
| `source_of_information` | string | ○ | ● | ● | مصدر المعلومة الأول |
| `confidence_level` | enum | ○ | ● | ● | low / medium / high — موثوقية البيانات |
| `created_by` | user_id | ● | ● | ● | |
| `approved_by` | user_id | ○ | ● | ● | |
| `approved_at` | datetime | ○ | ● | ● | |
| `reviewed_at` | datetime | ○ | ○ | ○ | آخر مراجعة دورية |
| `next_review_at` | date | ○ | ○ | ● | موعد المراجعة القادمة (B) |

---

## 2.3 عرض السجل في الواجهة

### تبويبات صفحة المسجّل

```
┌─────────────────────────────────────────────────────────┐
│  [زائر] أحمد علي محمد          مسجل #: V-2026-0042     │
│  ████████░░  خطورة: متوسطة                              │
├──────────┬──────────┬──────────┬──────────┬────────────┤
│  الهوية  │  المظهر  │  جنائي   │  الحوادث │  الوسائط  │
├──────────┴──────────┴──────────┴──────────┴────────────┤
│  (محتوى التبويب النشط)                                  │
└─────────────────────────────────────────────────────────┘
```

### شارة النوع (Badge)

| النوع | اللون | النص |
|-------|-------|------|
| زائر | أزرق | `زائر` |
| مسجل A | أحمر | `مسجل A` |
| مسجل B | برتقالي | `مسجل B` |

---

## 2.4 قواعد التحقق (Validation)

### عند الإنشاء

```php
// زائر: الحد الأدنى
required: registration_type, full_name, nationality, gender, passport_number

// مسجل A: الحد الأدنى
required: registration_type, full_name, national_id, birth_date, gender,
          nationality, risk_level, legal_status, modus_operandi, at_least_one_address

// مسجل B: الحد الأدنى
required: registration_type, full_name, gender, nationality,
          risk_level, legal_status, at_least_one_address
```

### عند الاعتماد (`is_published = true`)

- مسجل A: يتطلب موافقة **Admin** أو **Moderator**
- مسجل B: يتطلب موافقة **Moderator**
- زائر: يتطلب موافقة **Moderator**؛ يمكن نشره ببيانات جزئية

---

## 2.5 البحث المتاح حسب الحقول

| حقل البحث | زائر | A | B |
|-----------|:----:|:-:|:-:|
| الاسم / الأسماء المستعارة | ✅ | ✅ | ✅ |
| رقم الجواز | ✅ | ✅ | ✅ |
| الرقم القومي | — | ✅ | ✅ |
| الجنسية | ✅ | ✅ | ✅ |
| المظهر / العلامات | ✅ | ✅ | ✅ |
| أسلوب الجريمة | ⚠️ | ✅ | ✅ |
| المنطقة / العنوان | ✅ | ✅ | ✅ |
| نوع التسجيل | ✅ | ✅ | ✅ |
| مستوى الخطورة | — | ✅ | ✅ |

---

## 2.6 ملخص أعداد الحقول

| الفئة | عدد الحقول/العلاقات |
|-------|---------------------|
| الهوية والتعريف | 22 |
| الوصف الجسدي | 14 |
| الاتصال والعناوين | 15+ (علاقات) |
| الوصف الجنائي | 20+ (علاقات) |
| الأنماط الزمنية | 6 |
| الوسائط | 10+ (علاقات) |
| الحالة الإدارية | 14 |
| **الإجمالي** | **~100+ حقل وعلاقة** |
