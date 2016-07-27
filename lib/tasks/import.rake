#encoding: utf-8 
require 'csv'

def format_cep(cep)
  if !cep.blank? && cep != "NULL" && cep.length > 5
    cep.insert(5, '-') unless cep.split("").include? "-"
  end
  return cep
end

def news_category(cat)
  case cat
    when "3"
      "Transforme você mesmo"
    when "4"
      "Aplicativos"
    when "5"
      "Psicologia Infantil"
    when "7"
      "Jogos e brincadeiras"
    when "9"
      "Educação"
    when "10"
      "Fique ligado"
    when "11"
      "Culinária a quatro mãos"
    when "12"
      "Aqui é um ponto de trocas"
    when "13"
      "Consciência e Consumo"
    when "14"
      "Festa Infantil"
    else
      "--"
  end
end

namespace :import do
  desc "Import users"
  task :users => [:environment] do

    file = "db/import/users.csv"

    users_created = 0
    users_invalid = 0

    CSV.foreach(file) do |row|

      id = row[0]
      name = row[1]
      avatar = row[2]
      birth_date = row[3]
      gender = row[4]
      cpf = row[5]
      phone = row[6]
      zip_code = row[7]
      address = row[8]
      address_no = row[9]
      complement = row[10]
      city = row[11]
      neighborhood = row[12]
      state = row[13]
      email = row[14]
      password = row[15]
      salt = row[16]
      newsletter = row[17]
      recovery_code = row[18]

      user_ex = User.find_by_email(email)
      # user_ex.destroy if user_ex

      if !user_ex
        user = User.new
      
        user.id = id == "NULL" ? nil : id
        user.name = name == "NULL" ? nil : name
        if File.exist? File.expand_path Rails.root.join('public', "uploads/uploads/image/#{avatar}")
          begin
            user.avatar = avatar == "NULL" || avatar == "avatar.jpg" ? nil : File.open(Rails.root.join('public', "uploads/uploads/image/#{avatar}"))
          rescue ActiveRecord::RecordInvalid => e
            puts "erro upload...."
            puts e
          end
        end
        user.birthday = birth_date == "NULL" ? nil : birth_date
        user.gender = gender == "NULL" ? nil : gender.upcase
        # user.cpf = cpf == "NULL" ? nil : cpf
        user.phone = phone == "NULL" ? nil : phone
        user.zipcode = zip_code == "NULL" ? nil : format_cep(zip_code)
        user.street = address == "NULL" ? nil : address.humanize
        # user.address_no = address_no == "NULL" ? nil : address_no
        # user.complement = complement == "NULL" ? nil : complement
        # user.neighborhood = neighborhood == "NULL" ? nil : neighborhood
        user.city = city == "NULL" ? nil : city.humanize
        user.state = state == "NULL" ? nil : state.upcase
        user.email = email == "NULL" ? nil : email
        user.password = password == "NULL" ? nil : password
        # user.salt = salt == "NULL" ? nil : salt
        # user.newsletter = newsletter == "NULL" ? nil : newsletter
        # user.recovery_code = recovery_code == "NULL" ? nil : recovery_code
        
        puts id
        # puts user.valid?
        puts user.errors.full_messages if !user.valid?

        user.save if user.valid?

        users_invalid+1 if !user.valid?
        if user.save
          users_created+1
        end
        puts "#{zip_code} - #{format_cep(zip_code)} - "
      end

      puts "------------------------------------------------------------------------"

    end

    puts " "
    puts "Usuarios criados: #{users_created}"
    puts "Usuarios invalidis: #{users_invalid}"

  end


  desc "Import toys"
  task :toys => [:environment] do

    Toy.destroy_all

    ToyAge.destroy_all
    CSV.foreach("db/import/toy_age.csv") do |row|
      ToyAge.create id: row[0], title: row[1]
    end

    ToyCategory.destroy_all
    CSV.foreach("db/import/toy_category.csv") do |row|
      ToyCategory.create id: row[0], title: row[1]
    end

    file = "db/import/toys.csv"

    toys_existentes = 0

    CSV.foreach(file) do |row|

      id = row[0]
      cms_toy_brand_id = row[1]
      cms_toy_category_id = row[2]
      cms_toy_age_id = row[3]
      cms_client_id = row[4]
      cms_toy_city_id = row[5]
      name = row[6]
      description = row[7]
      weight = row[8]
      approved = row[9]
      message = row[10]
      deleted = row[11]
      created_at = row[12]
      brand_interest = row[13]
      age_interest = row[14]
      category_interest = row[15]

      # toy_ex = Toy.find_by_name(name)
      # toy_ex.destroy if user_ex

      toy = Toy.new
      
      toy.id = id == "NULL" ? nil : id
      # toy.name = cms_toy_brand_id == "NULL" ? nil : cms_toy_brand_id
      toy.toy_category_id = cms_toy_category_id == "NULL" ? nil : cms_toy_category_id
      toy.toy_age_id = cms_toy_age_id == "NULL" ? nil : cms_toy_age_id
      toy.user_id = cms_client_id == "NULL" ? nil : cms_client_id
      # toy.xxx = cms_toy_city_id == "NULL" ? nil : cms_toy_city_id
      toy.title = name == "NULL" ? nil : name.humanize
      toy.description = description == "NULL" ? nil : description
      toy.created_at = created_at == "NULL" ? nil : created_at

      toy.zipcode = toy.user ? toy.user.zipcode : User.limit(100).map(&:zipcode).uniq.compact.sample
      # toy.zipcode = User.limit(500).map(&:zipcode).uniq.compact.sample
      # toy.latitude = "65656"
      # toy.longitude = "65656"
      
      # puts toy
      # puts toy.valid?
      puts row[0]
      puts toy.inspect if !toy.valid?
      puts toy.errors.full_messages if !toy.valid?

      if toy.save
        toys_existentes+1
      end
      # toy.toy_images.create! image: File.open(Rails.root.join('public', "uploads/uploads/image/#{avatar}")) if toy.valid?

      puts "------------------------------------------------------------------------"
    end

    puts " "
    puts "Brinquedos cadastrados: #{toys_existentes}"

  end

  desc "Import toy images"
  task :toy_images => [:environment] do

    ToyImage.destroy_all
    CSV.foreach("db/import/toy_image.csv") do |row|

      puts "..............................................."
      id = row[0]
      toy_id = row[1]
      name = row[2]
      image = row[3]

      puts toy_id
      toy = Toy.find_by_id(toy_id)
      # puts toy

      
      if File.exist? File.expand_path Rails.root.join('public', "uploads/uploads/image/#{image}")
        begin
          toy.toy_images.create! featured: (name == "main" ? true : false), image: File.open(Rails.root.join('public', "uploads/uploads/image/#{image}")) if toy
        rescue ActiveRecord::RecordInvalid => e
          puts e
        end
      end

    end
    
  end

  desc "Import exchange"
  task :exchanges => [:environment] do

    Exchange.destroy_all
    CSV.foreach("db/import/exchange.csv") do |row|

      puts "..............................................."
      id = row[0]
      from_toy = row[1]
      to_toy = row[2]
      created_at = row[3]
      exchange_type = row[4] # 0=ponto de troca, 1=correio
      finalized = row[5]
      rating_from = row[6]
      rating_to = row[7]
      finalized_at = row[8]
      accepted = row[9]

      exchange = Exchange.new
      
      exchange.id = id == "NULL" ? nil : id
      exchange.toy_from = from_toy == "NULL" ? nil : from_toy
      exchange.toy_to = to_toy == "NULL" ? nil : to_toy
      exchange.created_at = created_at == "NULL" ? nil : created_at
      exchange.exchange_deliver = exchange_type == "NULL" ? nil : exchange_type
      exchange.exchange_type = "exchange"
      exchange.finalized = finalized == "NULL" ? nil : finalized
      exchange.finalized_at = finalized_at == "NULL" ? nil : finalized_at
      exchange.accepted = accepted == "NULL" ? nil : accepted
      exchange.user_id = Toy.find_by_id(from_toy) && Toy.find_by_id(from_toy).user ? Toy.find_by_id(from_toy).user.id : nil

      puts id
      puts exchange.inspect if !exchange.valid?
      puts exchange.errors.full_messages if !exchange.valid?

      if exchange.save
        puts "saved"
      end

    end
    
  end

  desc "Import exchange message"
  task :exchange_messages => [:environment] do

    ExchangeMessage.destroy_all
    CSV.foreach("db/import/exchange_message.csv") do |row|

      # puts row

      puts "..............................................."
      id = row[0]
      cms_exchange_id = row[1]
      from_client = row[2]
      to_client = row[3]
      created_at = row[4] # 0=ponto de troca, 1=correio
      message = row[5]

      exchange = ExchangeMessage.new
      
      exchange.id = id == "NULL" ? nil : id
      exchange.exchange_id = cms_exchange_id == "NULL" ? nil : cms_exchange_id
      exchange.user_from = from_client == "NULL" ? nil : from_client
      exchange.user_to = to_client == "NULL" ? nil : to_client
      exchange.created_at = created_at == "NULL" ? nil : created_at
      exchange.message = message == "NULL" ? nil : message

      puts id
      puts exchange.inspect if !exchange.valid?
      puts exchange.errors.full_messages if !exchange.valid?

      if exchange.save
      end

    end
    
  end

  desc "Import news"
  task :news => [:environment] do

    Article.destroy_all
    CSV.foreach("db/import/news.csv") do |row|

      # puts row

      puts "..............................................."
      id = row[0]
      cms_news_author_id = row[1]
      cms_news_category_id = row[2]
      name = row[3]
      # friendly_url = row[4]
      content = row[5] # 0=ponto de troca, 1=correio
      publicated_at = row[6]
      cover_image = row[7]
      # ordering = row[8]
      active = row[9]
      # show_footer = row[10]

      article = Article.new
      
      # article.id = id == "NULL" ? nil : id
      article.user_id = cms_news_author_id == "NULL" ? nil : cms_news_author_id
      article.category = cms_news_category_id == "NULL" ? nil : news_category(cms_news_category_id)
      article.title = name == "NULL" ? nil : name
      article.body = content == "NULL" ? nil : content
      article.created_at = publicated_at == "NULL" ? nil : publicated_at
      article.published_at = publicated_at == "NULL" ? nil : publicated_at
      article.active = active == "NULL" ? nil : active

      puts cover_image
      puts File.exist? File.expand_path Rails.root.join('public', "uploads/uploads/image/#{cover_image}")

      if File.exist? File.expand_path Rails.root.join('public', "uploads/uploads/image/#{cover_image}")
        puts "tem imagem"
        begin
          article.cover = cover_image == "NULL" || cover_image == "avatar.jpg" ? nil : File.open(Rails.root.join('public', "uploads/uploads/image/#{cover_image}"))
        rescue ActiveRecord::RecordInvalid => e
          puts "erro upload...."
          puts e
        end
      end

      puts id
      puts article.inspect if !article.valid?
      puts article.errors.full_messages if !article.valid?

      if article.save
      end

    end
    
  end

end