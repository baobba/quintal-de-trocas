class QuintalMailer < ApplicationMailer

  def welcome_email(user)
    @user = user
    mail(to: @user.email, subject: 'Bem vindo')
  end

  def send_exchange_message(exchange, user)
    @exchange = exchange
    @user = user
    mail(to: @user.email, subject: 'Nova solicitação de troca recebida')
  end

  def toy_added(toy)
    @toy = toy
    @user = @toy.user
    mail(to: @user.email, subject: 'Seu produto foi recebido')
  end
end
