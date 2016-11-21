# This file should contain all the record creation needed to seed the database with its default values.
# The data can then be loaded with the rake db:seed (or created alongside the db with db:setup).
#
# Examples:
#
#   cities = City.create([{ name: 'Chicago' }, { name: 'Copenhagen' }])
#   Mayor.create(name: 'Emanuel', city: cities.first)

AdminUser.destroy_all
AdminUser.create!(email: 'admin@example.com', password: 'admin123', password_confirmation: 'admin123')


# create users
User.destroy_all
User.create email: "netto16@gmail.com", password: "admin123", name: "Osny", birthday: 1989, gender: "M", phone: "48 99355794", username: "osnysantos", zipcode: "88110690", street: "Rua Bernardo Halfeld, 471", city: "São José", state: "SC", latitude: -27.5807659, longitude: -48.6194795, neighborhood: "xxx"
User.create email: "test@test.com", password: "admin123", name: "Joao", birthday: 2000, gender: "M", phone: "48 788728787", username: "joao", zipcode: "88111120", neighborhood: "xxx"


# create toy categories
ToyCategory.destroy_all
toy_categories = ["Bonecas", "Veículos Pequenos", "Veículos Grandes", "Quebra-cabeça", "Para montar", "Musicais", "Livros", "Jogos", "Fantasia", "Faz de conta", "Esportes", "Casinha de Boneca", "Bonecos", "Lego", "Outros"]
toy_categories.each do |t|
  ToyCategory.create title: t
end


# create toy categories
ToyAge.destroy_all
toy_ages = ["0 a 12 meses", "1 a 2 anos", "3 a 6 anos", "Mais de 7 anos", "Indefinida"]
toy_ages.each do |t|
  ToyAge.create title: t
end


# create toys
Toy.destroy_all
(1..20).each_with_index do |a, index|
  a=Toy.create title: "Brinquedo #{index}", description: "...", toy_category_id: ToyCategory.all.map(&:id).sample, toy_age_id: ToyAge.all.map(&:id).sample || 0, user_id: User.all.map(&:id).sample
  a.toy_images.create! image: File.open(Rails.root.join('public', "toy_images/image0#{[*1..6].sample}.jpg"))
end

# create places
Place.destroy_all
places_names = ["Casa do Brincar", "Areté", "Playtoy", "Casa das Ideias"]
ceps = ["07243-080", "93145-174", "13025-070", "02178-010", "07195-120", "37400-000", "07790-190", "90220-040", "18044-645", "13347-180", "11680-000", "13560-460", "93330-100", "85568-000", "05112-010", "12246-001", "95280-000", "09271-510", "54310-420", "03211-000", "01034-010", "42807-180", "04321-002", "03042-001", "12414-270", "18087-157", "04557-000", "09090-710", "06765-130", "13481-149"]
(1..20).each_with_index do |index|
  Place.create title: places_names.sample, office_hours: "Seg. a Sex. das 9h as 18h", phone: "11-3032-2323", street: "Rua Ferreira de Araújo, 388", state: "SP", user_id: User.all.map(&:id).sample, zipcode: ceps.sample
end


Article.destroy_all
article_titles = ["A experiência da troca", "A troca na aldeia do Kamayuras", "Pedalando no Quintal, mas sem pedal ?"]
(1..30).each_with_index do |index|
  Article.create title: article_titles.sample, category: "Geral", user_id: User.all.map(&:id).sample, body: "Um dia do mês de fevereiro de 2014, estava eu navegando pela rede social Facebook, quando surgiu em meu feed de notícias um post chamado Quintal de Trocas, tratava-se de um vídeo muito interessante. Depois que o assisti fui pesquisar o assunto no site. E simplesmente fiquei encantada com o que descobri. Que ideia genial!!! - pensei. Expliquei para minha filha sobre a possibilidade de trocar seus brinquedos usados por outros e ela ficou bastante empolgada. Então selecionamos os brinquedos e em menos de 1 mês fizemos duas trocas. A primeira pelo correio com uma criança de Brasília, e a segunda pessoalmente, no Extra Anchieta/SP; experiências muito divertidas e também angustiosas, pois como é difícil para uma criança entre 0 a 10 anos aprender a esperar, aprender que nem tudo se resolve na hora que se quer, e nem do jeito que se deseja. (Foi um bom momento para ensinar o que significa planejar nossas ações)"
end
