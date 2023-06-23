<?php

namespace Database\Seeders;

use App\Models\Faq;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faqs = [
            [
                'question' => 'HFS có những sản phẩm thực phẩm nào?',
                'answer' => 'Chúng tôi cung cấp một loạt các sản phẩm thực phẩm lành mạnh bao gồm rau, quả tươi, hạt, ngũ cốc, thịt và các xuất ăn có sẵn được chế biến từ thực phẩm do chúng tôi cung cấp.'
            ],
            [
                'question' => 'HFS có những sản phẩm hữu cơ không?',
                'answer' => 'Vâng, chúng tôi có một số sản phẩm hữu cơ trong cửa hàng của chúng tôi. Chúng tôi cam kết mang đến cho khách hàng những lựa chọn thực phẩm hữu cơ chất lượng cao.'
            ],
            [
                'question' => 'Các sản phẩm của HFS có đảm bảo không chứa chất bảo quản hay phẩm màu nhân tạo không?',
                'answer' => 'Vâng, chúng tôi có một số sản phẩm hữu cơ trong cửa hàng của chúng tôi. Chúng tôi cam kết mang đến cho khách hàng những lựa chọn thực phẩm hữu cơ chất lượng cao.'
            ],
            [
                'question' => 'Có những lựa chọn ăn kiêng hay phù hợp cho những người có nhu cầu ăn theo chế độ ăn đặc biệt (như tối ưu calories, tối ưu chất béo xấu, tối ưu các thành phần dinh dưỡng, vv)?',
                'answer' => 'Vâng, chúng tôi có hỗ trợ khách hàng order xuất ăn theo lượng calo mà khách hàng muốn ăn và tối ưu các thành phần dinh dưỡng trong bửa ăn của khách hàng. Chúng tôi cam kết mang đến cho khách hàng những lựa chọn thực phẩm chất lượng cao.'
            ],
            [
                'question' => 'HFS có những sản phẩm chứa protein thực vật (như đậu, hạt, lạc, vv) không?',
                'answer' => 'Đúng vậy, chúng tôi có nhiều sản phẩm chứa protein thực vật như đậu, hạt, lạc, đỗ, và các sản phẩm thực phẩm chế biến từ chúng.'
            ],
            [
                'question' => 'Sản phẩm của HFS có nguồn gốc từ những nguồn cung cấp đáng tin cậy không?',
                'answer' => 'Chúng tôi chỉ lựa chọn các nhà cung cấp đáng tin cậy và các nguồn gốc sản phẩm uy tín. Chúng tôi cam kết đảm bảo chất lượng và an toàn cho khách hàng.'
            ],
            [
                'question' => 'Các sản phẩm của HFS có được chứng nhận hoặc được kiểm tra độ an toàn không?',
                'answer' => 'Chúng tôi đặt một sự tôn trọng cao đối với các sản phẩm của mình. Một số sản phẩm trong cửa hàng của chúng tôi có chứng nhận và chúng tôi cũng thực hiện các kiểm tra độ an toàn để đảm bảo chất lượng của sản phẩm.'
            ],
            [
                'question' => 'HFS có hỗ trợ sẳn thực đơn dựa trên nhu cầu không?',
                'answer' => 'Đúng vậy, chúng tôi luôn luôn cung cấp danh sách gồm 4 xuất ăn khác nhau (dựa trên sở thích, đa dạng thành phần ăn trong ngày và đưa ra xuất ăn nếu trường hợp khách hàng cảm thấy ngán) trong 1 bửa ăn. Khách hàng có thể lựa chọn dựa trên mong muốn của khách hàng.'
            ],
            [
                'question' => 'Có những tùy chọn thức uống lành mạnh như nước ép trái cây tươi, sinh tố, hay trà thảo mộc không?',
                'answer' => 'Hiện tại chung tôi chưa cung cấp các sản phẩm như thức uống lành mạnh. Nhưng trong tương lai sắp tới các sẳn phẩm này sẽ sớm xuất hiện trên cửa hàng của chúng tôi.'
            ],
            [
                'question' => 'HFS có những thông tin dinh dưỡng chi tiết về các sản phẩm mà bạn bán không?',
                'answer' => 'Tất nhiên! Chúng tôi cung cấp thông tin dinh dưỡng chi tiết về các sản phẩm mà chúng tôi bán. Chúng tôi hiểu rằng thông tin dinh dưỡng là quan trọng đối với khách hàng khi lựa chọn thực phẩm lành mạnh. Bạn có thể tìm thấy thông tin về thành phần dinh dưỡng, lượng calo, chất béo, carbohydrate, protein, các loại khoáng chất, cũng như các thông tin khác liên quan đến sự dinh dưỡng của sản phẩm trên nhãn sản phẩm trong cửa hàng. Chúng tôi mong rằng thông tin này sẽ giúp bạn đưa ra quyết định thông thái về lựa chọn thực phẩm phù hợp với nhu cầu dinh dưỡng và sở thích của bạn.'
            ],
            [
                'question' => 'HFS có cung cấp thực phẩm chức năng không?',
                'answer' => 'Rất tiếc! Hiện tại chúng tôi chỉ cung cấp xuất ăn và thành phần ăn dinh dưỡng, nhưng trong tương lai chúng tôi sẽ sớm cung cấp các thực phẩm chức năng.'
            ],
            // Thêm các câu hỏi và câu trả lời khác vào đây
        ];

        foreach ($faqs as $faq) {
            Faq::create($faq);
        }
    }
}
