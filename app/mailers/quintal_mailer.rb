class QuintalMailer < ApplicationMailer

  def welcome_email(user)
    @user = user
    mail(to: @user.email, subject: 'Bem vindo')
  end

  def request_exchange(exchange)
    @exchange = exchange
    @user_from = exchange.from_user
    @user_to = exchange.to_user
    mail(to: @user_to.email, subject: 'Nova solicitação de troca recebida')
  end

  def new_exchange_message(exchange, user_from, user_to)
    @exchange = exchange
    @user_from = user_from
    @user_to = user_to

    mail(to: @user_to.email, subject: 'Você recebeu uma nova mensagem')
  end

  def exchange_changed(exchange, user, current_user)
    @exchange = exchange
    @user = user
    @current_user = current_user
    @status = exchange.exchange_type != "canceled" ? "aceitou" : "não aceitou"

    mail(to: @user.email, subject: "#{@current_user.first_name} #{@status} sua solicitação")
  end

  def item_added(item)
    @item = item
    @user = @item.user
    mail(to: @user.email, subject: 'Seu produto foi recebido')
  end

  def contact_us(message)
    @message = message
    mail(to: "carolguedes.atriz@gmail.com", cc: "netto16@gmail.com", subject: 'Novo contato pelo site')
  end

  # 
  def check_your_items(user)
    @user = user
    mail(to: user.email, subject: 'Novidades... novo site, logotipo e geolocalização')
  end

  def item_reminder(item)
    @item = item
    @user = item.user
    mail(to: @user.email, subject: 'Seu brinquedo está prestes a expirar')
  end

  def item_arrived(exchange, user)
    @exchange = exchange
    @user_from = user
    @user_to = User.find_by_id([exchange.user_id, exchange.user_to].reject{ |e| e == user.id }.first)

    mail(to: @user_to.email, subject: 'Recebi meu brinquedo')
  end
end
