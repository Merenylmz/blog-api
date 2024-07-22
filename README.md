

# Clone Project (Projeyi Klonla)

Projemizi Klonlıyalım şimdi ilk yapmamız gereken git yüklemek giti kendi sayfasından indirebilirsiniz "https://git-scm.com/downloads"
git'i indirdikten sonra projemiz için bir dosya oluşturup üzerine git bash terminalini açıyoruz. 
sonrasında "git clone https://github.com/Merenylmz/blog-api.git" 
komutunu çalıştırıp projemizi oluşturduğunuz dosya içine kopyalıyorsunuz.

Proje kopyalandıktan sonra birkaç işlem daha yapmamız gerekiyor;

1.Projeyi Docker Üzerinden çalıştırmak için "docker-compose up -d" komutunu çalıştırın.
2. Projemizde bulunan .env.example dosyasını .env dosyasına kopyalamamız gerekir bunun için "copy .env.example .env" komutunu çalıştırıyoruz.(.env dosyası kendi oluşuyor.)
3. Projemizin Paket ve Bağımlılıklarını indirmek için "composer install" komutunu yazıyoruz (composer kurulu olmalıdır.)
4. ".env" dosyasında bulunan mail kısmına kendi, çalışan mail adresimizi ve hostumuzu giriyoruz.
5. Yeni Kullanıcı Oluşturuyoruz, bunun için "php artisan make:filament-user" komutunu kullanıp bilgilerimizi giriyoruz
6. Kullanıcıya admin rolü verebilmek içinse, "php artisan shield:install" komutunu çalıştırıyoruz

Projeyi bu şekilde klonlayabiliriz.

// English

Let's clone our project, now the first thing we need to do is install git, you can download git from its own page “https://git-scm.com/downloads”
After downloading git, we create a file for our project and open the git bash terminal on it. 
then “git clone https://github.com/Merenylmz/blog-api.git” 
command and copy our project into the file you created.

After the project is copied, we need to do a few more steps;
1.Run the “docker-compose up -d” command to run the project via Docker.
2. We need to copy the .env.example file in our project to the .env file, for this we run the “copy .env.example .env” command.(.env file is created by itself.)
3. We type the command “composer install” to download the packages and dependencies of our project (composer must be installed.)
4. We enter our own, working e-mail address and host in the mail section in the “.env” file.
5. Create a new user, for this we use the command “php artisan make:filament-user” and enter our information
6. To give the user the admin role, we run the “php artisan shield:install” command

Translated with DeepL.com (free version)

This is how we can clone the project.

Translated with DeepL.com (free version)




# Repository Design Pattern

Burada Kullanmış olduğum Repository sistemi şu şekilde ilk başta ortak kullanıcağımız ve özel kullanıcağımız Repositorylerin interfacelerini tanımlıyoruz
# app/Interface/Common 
altında bulunan CommonRepositoryInterface dosyasında bütün interfacelerde ortak kullanıcağımız metotlar bulunuyor sonra biz bunları miras alıp o şekilde kullanabiliyoruz
sonrasında ise Repositorylerimizi class olarak tanımlıyoruz.
Ortak Repositorymizi oluşturuyoruz ve Ortak Interface'i içine implement ediyoruz.
# app/Repository/Common/CommonRepositoryTrait
yolunda ise bir Trait oluşturup her birine implement ettiğimiz özel interfacelerdeki ortak metotları orda tutuyoruz ve örneğin blogRepositoryden onu çağırıp use ifadesi
ile metotları aktarıyoruz.

Sonrasında birde Service katmanımızı ekliyoruz bu katmanda 2 tane klasör bu klasörlerden biri interface(abstract) diğeri ise class(concrete) için bulunuyor.
Service sistemindede Ortak kullanıma bağlı bulunan bir sistem bulunuyor.

# app/Services/Abstract/Common
yolunda kullanıcağımız ortak metotların imzalarını atıyoruz sonrasında bir üst dizine çıkıp özel interfacelerden ortak olanı mirası alıyoruz.
sonrasında ise bunları Service classlara implement ediyoruz.

# app/Services/Concrete/Common
yolunda implement edilen interfacelerde bulunan imzaları atılmış metotların içlerini yazıyoruz
sonrasında bir üst dizine çıkıp metotlarımıza traiti entegre ediyoruz


# Kullandığım bu sistemde sadece örneğin bloga özel bir metot yazmak istediğim zaman sadece onun interfaceleri, Repositoryleri ve Service(interface, classları) içine yazıyorum

sonrasında bu sistemi kullanabilmek için controllerdan servicei çağırıp içindeki metotları çekiyorum. api.phpde ise endpointleri yazıyorum.

# --------------------------------------------------------------------------------------------------------------------------------------------------------------------------
Providers klasöründe bulunan Filament dosyasında Filamentte kullandığım Tasarımların değişmiş halleri bulunuyor.
