# encoding: utf-8
namespace :quintal do
  desc "Envia lembrete para usuários perguntando se o brinquedo ainda está disponível"
  task :send_toys_reminder => [:environment] do
    Toy.joins(:user).where(next_notification_at: Date.today).each do |toy|
      puts "Toy: #{toy.title}"

      if !toy.next_notification_at.blank? && toy.expired_at.blank?

        QuintalMailer.toy_reminder(toy).deliver
        toy.update_column(:next_notification_at, Date.today + 2.days)
        toy.update_column(:expired_at, Time.now)
        puts "#{toy.user.email}, foi notificado por e-mail sobre brinquedo."

      elsif !toy.next_notification_at.blank?

        # Expira credito do usuario
        toy.credits.available.first.update_column(:expired_at, Time.now) if toy.credits && toy.credits.available && toy.credits.available.first

        toy.update_column(:expired_at, Time.now)
        toy.update_column(:deleted_at, Time.now)

        puts "#{toy.title}, foi excluido do Quintal."
      end

    end
  end
end