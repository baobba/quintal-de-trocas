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

  def new_exchange_message(exchange, user)
    @exchange = exchange
    @user = user
    mail(to: @user.email, subject: 'Você recebeu uma nova mensagem')
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
