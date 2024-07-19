<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CreateKvkkSeeder extends Seeder
{
    
    public function run(): void
    {
        DB::table("kvkkpage")->insert([
            "title"=>"KVKK Document",
            "description"=> `
                Kanun, kişisel verileri işlenen ilgili kişilere bu verilerinin kim tarafından, hangi amaçlarla ve hukuki sebeplerle işlenebileceği, kimlere hangi amaçlarla aktarılabileceği hususunda bilgi edinme hakkı tanımakta ve bu hususları, veri sorumlusunun aydınlatma yükümlülüğü kapsamında ele almaktadır. Buna göre veri sorumlusu, Kanunun 10. maddesi çerçevesinde kişisel verilerin elde edilmesi sırasında bizzat veya yetkilendirdiği kişi aracılığıyla aşağıdaki bilgileri ilgili kişiye sağlamakla yükümlüdür:
                Veri sorumlusunun ve varsa temsilcisinin kimliği,
                Kişisel verilerin hangi amaçla işleneceği,
                Kişisel verilerin kimlere ve hangi amaçla aktarılabileceği,
                Kişisel veri toplamanın yöntemi ve hukuki sebebi,
                11. maddede sayılan diğer hakları.
                Veri işleme faaliyetinin ilgili kişinin açık rızasına bağlı olduğu veya faaliyetin Kanundaki diğer bir şart kapsamında yürütüldüğü durumlarda da veri sorumlusunun ilgili kişiyi bilgilendirme yükümlülüğü devam etmektedir. Yani, ilgili kişi, kişisel verisinin işlendiği her durumda aydınlatılmalıdır.
            `
        ]);
    }
}
