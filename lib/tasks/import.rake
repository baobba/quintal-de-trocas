#encoding: utf-8 
require 'csv'
require 'action_view'
include ActionView::Helpers::SanitizeHelper

def format_cep(cep)
  if !cep.blank? && cep != "NULL" && cep.length > 5
    cep.insert(5, '-') unless cep.split("").include? "-"
  end
  return cep
end

def aws
  credentials = Aws::Credentials.new(ENV['AWS_ACCESS_KEY'], ENV['AWS_SECRET_KEY'])
  s3 = Aws::S3::Resource.new(
    credentials: credentials,
    region: 'us-east-1'
  )
end

def s3_url(name)
  "https://quintal-de-trocas.s3.amazonaws.com/#{name}"
end

def open_file(file)
  s3 = aws
  # puts s3.inspect
  # puts file
  puts s3.bucket('quintal-de-trocas').object(file)
  puts s3.bucket('quintal-de-trocas').object(file).public_url
  return open(s3.bucket('quintal-de-trocas').object(file).public_url)
end

def get_file(file)
  s3 = aws
  # puts s3.inspect
  # puts file
  puts s3.bucket('quintal-de-trocas').object(file)
  puts s3.bucket('quintal-de-trocas').object(file).public_url
  puts s3.bucket('quintal-de-trocas').object(file).exists?
  return s3.bucket('quintal-de-trocas').object(file)
end

def convert_category_names(cat)
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
  desc 'All'
  task all: [:users, :toys, :toy_images, :exchange, :exchange_messages, :news] do
  end

  desc "Import users"
  task :users => [:environment] do

    file = "public/system/import/users.csv"

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
        if s3_url("image/#{avatar}")
          begin
            user.avatar = avatar == "NULL" || avatar == "avatar.jpg" ? nil : get_file("image/#{avatar}").public_url
          rescue ActiveRecord::RecordInvalid => e
            puts "erro upload...."
            puts e
          rescue
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

  desc "Import users"
  task :users_1 => [:environment] do

    file = "public/system/import/users.csv"
    CSV.foreach(file) do |row|

      id = row[0]
      address_no = row[9]
      complement = row[10]
      neighborhood = row[12]
      newsletter = row[17]

      user = User.find_by_id(id)

      if user
        user.id = id == "NULL" ? nil : id
        user.street = address_no == "NULL" ? nil : [user.street, address_no].join(", ") if !user.street.blank? && !user.street.include?(",")
        # user.address_no = address_no == "NULL" ? nil : address_no
        user.complement = complement == "NULL" ? nil : complement
        user.neighborhood = neighborhood == "NULL" ? nil : neighborhood

        user.newsletter = newsletter == "NULL" ? nil : newsletter

        puts id
        puts user.street.to_s.include?(",")
        puts user.street
        puts user.errors.full_messages if !user.valid?
        user.save if user.valid?

        puts user.save
      end

      puts "------------------------------------------------------------------------"

    end

  end

  desc "Import users"
  task :users_2 => [:environment] do

    file = "public/system/import/users.csv"
    CSV.foreach(file) do |row|

      id = row[0]
      avatar = row[2]

      user = User.find_by_id(id)
      if user
        if s3_url("image/#{avatar}")
          begin
            user.remote_avatar_url = avatar == "NULL" || avatar == "avatar.jpg" ? nil : get_file("image/#{avatar}").public_url
          rescue ActiveRecord::RecordInvalid => e
            puts "erro upload...."
            puts e
          rescue
          end
        end

        puts id
        puts user.errors.full_messages if !user.valid?
        user.save if user.valid?

        if user.save
        end
      end

      puts "------------------------------------------------------------------------"

    end

  end


  desc "Import toys"
  task :toys => [:environment] do

    Toy.destroy_all

    ToyAge.destroy_all
    CSV.foreach("public/system/import/toy_age.csv") do |row|
      ToyAge.create id: row[0], title: row[1]
    end

    ToyCategory.destroy_all
    CSV.foreach("public/system/import/toy_category.csv") do |row|
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
      toy.latitude = toy.user ? toy.user.latitude : nil
      toy.longitude = toy.user ? toy.user.longitude : nil
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
    CSV.foreach("public/system/import/toy_image.csv") do |row|

      puts "..............................................."
      id = row[0]
      toy_id = row[1]
      name = row[2]
      image = row[3]

      puts toy_id
      toy = Toy.find_by_id(toy_id)
      # puts toy

      toy_i = ToyImage.find_by_id(id)
      if !toy_i
        if toy && s3_url("image/#{image}")
          begin
            toy.toy_images.create! id: id,
              featured: (name == "main" ? true : false),
              remote_image_url: get_file("image/#{image}").public_url
            puts "salvou"
          rescue ActiveRecord::RecordInvalid => e
            puts e
          end
        end
      else
        "ja possui imagem"
      end

    end
    
  end

  desc "Import exchange"
  task :exchanges => [:environment] do

    Exchange.destroy_all
    CSV.foreach("public/system/import/exchange.csv") do |row|

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
    CSV.foreach("public/system/import/exchange_message.csv") do |row|

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
    CSV.foreach("public/system/import/news.csv") do |row|

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
      
      article_ex = Article.find(id)

      if !article_ex
        article = Article.new
      
        article.id = id == "NULL" ? nil : id
        article.user_id = User.find_by_id(cms_news_author_id) ? User.find_by_id(cms_news_author_id).id : nil
        article.category = cms_news_category_id == "NULL" ? nil : convert_category_names(cms_news_category_id)
        article.title = name == "NULL" ? nil : name
        article.body = content == "NULL" ? nil : content
        article.created_at = publicated_at == "NULL" ? nil : publicated_at
        article.published_at = publicated_at == "NULL" ? nil : publicated_at
        article.active = active == "NULL" ? nil : active

        if s3_url("image/#{cover_image}") 
          puts "tem imagem"
          begin
            article.remote_cover_url = cover_image == "NULL" || cover_image == "avatar.jpg" ? nil : get_file("image/#{cover_image}").public_url
          rescue ActiveRecord::RecordInvalid => e
            puts "erro upload...."
            puts e
          rescue
          end
        end
      end

      puts id
      puts article.inspect if !article.valid?
      puts article.errors.full_messages if !article.valid?

      if article.save
      end

    end
    
  end

  desc "Import exchange point"
  task :places => [:environment] do

    Place.destroy_all
    CSV.foreach("public/system/import/exchange_point.csv") do |row|

      puts "..............................................."
      id = row[0]
      name = row[1]
      address = row[2]
      active = row[3]
      ordering = row[4]
      address_no = row[5]
      zip_code = row[6]
      complement = row[7]
      state = row[8]
      city = row[9]
      neighborhood = row[10]
      phone = row[11]
      image = row[12]
      offer = row[13]
      description = row[14]

      place = Place.new
      
      place.id = id == "NULL" ? nil : id
      place.title = name == "NULL" ? nil : strip_tags(name)
      place.street = address == "NULL" ? nil : address
      place.is_active = active == "NULL" ? nil : active
      # place.address_no = address_no == "NULL" ? nil : address_no
      place.zipcode = zip_code == "NULL" ? nil : format_cep(zip_code)
      place.neighborhood = complement == "NULL" ? nil : complement
      place.state = state == "NULL" ? nil : state.upcase
      place.city = city == "NULL" ? nil : city
      place.office_hours = neighborhood == "NULL" ? nil : neighborhood
      place.phone = phone == "NULL" ? nil : phone

      puts id
      puts place.inspect if !place.valid?
      puts place.errors.full_messages if !place.valid?

      if place.save
      end

    end
    
  end

end