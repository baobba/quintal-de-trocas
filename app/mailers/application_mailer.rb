class ApplicationMailer < ActionMailer::Base
  default from: "Quintal de Trocas <notificacoes@quintaldetrocas.com.br>",
          reply_to: "Quintal de Trocas <contato@quintaldetrocas.com.br>",
          "x-mailgun-native-send" => true

  layout 'mailer'
end
