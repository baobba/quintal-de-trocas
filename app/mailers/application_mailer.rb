class ApplicationMailer < ActionMailer::Base
  default from: "Quintal de Trocas <notificacoes@quintaldetrocas.com.br>",
          "x-mailgun-native-send" => true

  layout 'mailer'
end
