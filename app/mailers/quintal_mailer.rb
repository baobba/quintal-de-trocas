class QuintalMailer < ApplicationMailer

  def welcome_email(user)
    @user = user
    mail(to: @user.email, subject: 'Bem vindo')
  end

  def request_exchange(exchange, user)
    @exchange = exchange
    @user = user
    mail(to: @user.email, subject: 'Nova solicitação de troca recebida')
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

  def toy_added(toy)
    @toy = toy
    @user = @toy.user
    mail(to: @user.email, subject: 'Seu produto foi recebido')
  end

  def contact_us(message)
    @message = message
    mail(to: "carol@quintaldetrocas.com.br", subject: 'Novo contato pelo site')
  end

  # 
  def check_your_toys(user)
    @user = user
    mail(to: user.email, subject: 'Novidades... novo site, logotipo e geolocalização')
  end
end
