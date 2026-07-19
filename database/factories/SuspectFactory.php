<?php

namespace Database\Factories;

use App\Models\Suspect;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Suspect>
 */
class SuspectFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = Suspect::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $firstNames = [
            'أحمد', 'محمد', 'يوسف', 'إبراهيم', 'محمود', 'خالد', 'سامي', 'عادل', 'حسام', 'عبدالله',
            'سارة', 'فاطمة', 'هند', 'ريم', 'ياسمين', 'سلمى', 'نجلاء', 'نور', 'منة',
            'عبد الرحمن', 'أبو بكر', 'مصطفى', 'طارق', 'وليد', 'أشرف', 'عمرو', 'شريف', 'سامح', 'معتز',
        ];

        $lastNames = [
            'السيد', 'عبد الرحمن', 'الفتاح', 'محمود', 'الليث', 'الشاذلي', 'فتحي', 'إسماعيل', 'عبد العال',
            'السعيد', 'أبو زيد', 'المنصوري', 'حسن', 'عمر', 'سعيد', 'النجار', 'عبد الحكيم', 'مبارك', 'قاسم',
            'الحاج', 'بدوي', 'عبد الله', 'رمضان', 'الطاهر', 'خليل', 'نجيب', 'محروس', 'علي', 'خيري',
        ];

        $addresses = [
            'القاهرة - المعادي', 'القاهرة - الزمالك', 'القاهرة - مصر الجديدة', 'القاهرة - مدينة نصر',
            'الإسكندرية - سان ستيفانو', 'الإسكندرية - سيدي بشر', 'الجيزة -6 أكتوبر', 'الجيزة - الدقي',
            'الجيزة - الشيخ زايد', 'المنصورة - حي الجامعة', 'المنوفية - شبين الكوم', 'الأقصر - الطيبه',
            'أسيوط - مركز أبنوب', 'بورسعيد - الجنوب', 'السويس - فيصل', 'الشرقية - العاشر من رمضان',
            'المنيا - ملوي', 'الدقهلية - المنزلة', 'قنا - city', 'أسوان - أدفو',
        ];

        $registrationCategories = ['مشتبه', 'متورط', 'مطلوب', 'مراقبة', 'مؤقت'];
        $dangerLevels = ['منخفض', 'متوسط', 'مرتفع', 'حرج'];
        $criminalActivities = [
            'سلب', 'اختلاس', 'ترويج مخدرات', 'سرقة سيارات', 'احتيال', 'اعتداء', 'تزوير مستندات', 'غش تجاري', 'تهريب', 'اختطاف',
        ];
        $statuses = ['نشط', 'موقوف', 'محرر', 'قيد المتابعة', 'مؤجل', 'مستبعد'];
        $bodyBuilds = ['نحيف', 'متوسط', 'عضلي', 'بدين', 'رياضي'];
        $skinColors = ['أبيض', 'أشقر', 'أسمر', 'غامق', 'متوسط'];
        $distinguishingMarks = [
            'ندبة على اليمين', 'شامة على الذقن', 'أصابع مكسورة', 'سن مفقود', 'طول في الندبات', 'شعر أشقر', 'أطراف ملتصقة', 'خاتم ذهبي',
        ];

        return [
            'full_name' => fake()->randomElement($firstNames) . ' ' . fake()->randomElement($lastNames),
            'alias_name' => fake()->randomElement($firstNames) . ' ' . fake()->randomElement($lastNames),
            'national_id' => fake()->numerify('##############'),
            'birth_date' => fake()->dateTimeBetween('-70 years', '-18 years')->format('Y-m-d'),
            'current_address' => fake()->randomElement($addresses),
            'registration_category' => fake()->randomElement($registrationCategories),
            'danger_level' => fake()->randomElement($dangerLevels),
            'criminal_activity' => fake()->randomElement($criminalActivities),
            'current_status' => fake()->randomElement($statuses),
            'distinguishing_marks' => fake()->randomElement($distinguishingMarks) . ' - ' . fake()->word(),
            'height_cm' => fake()->numberBetween(150, 205),
            'body_build' => fake()->randomElement($bodyBuilds),
            'skin_color' => fake()->randomElement($skinColors),
            'profile_image_path' => fake()->optional()->imageUrl(300, 300, 'people') ?? 'storage/profile_images/' . fake()->uuid() . '.jpg',
        ];
    }
}
