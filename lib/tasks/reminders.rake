# encoding: utf-8
namespace :quintal do
  desc "Envia lembrete para usuários perguntando se o brinquedo ainda está disponível"
  task :send_items_reminder => [:environment] do

    email_list = []
    emails_count = 0

    Item.joins(:user).where(next_notification_at: Date.today).each do |item|
      puts "Item: #{item.title}"

      if !item.next_notification_at.blank? && item.expired_at.blank?

        if !email_list.include?(item.user.email)
          QuintalMailer.item_reminder(item).deliver
          item.update_column(:next_notification_at, Date.today + 2.days)
          item.update_column(:expired_at, Time.now)
          puts "#{item.user.email}, foi notificado por e-mail sobre brinquedo."
          email_list.push(item.user.email)
          emails_count = emails_count+1

          # Sleep 1 hour before continue sending emails, prevent spam detection
          sleep(1.hour) if emails_count == 150
        end

      elsif !item.next_notification_at.blank?

        # Expira credito do usuario
        item.credits.available.first.update_column(:expired_at, Time.now) if item.credits && item.credits.available && item.credits.available.first

        item.update_column(:expired_at, Time.now)
        item.update_column(:deleted_at, Time.now)

        puts "#{item.title}, foi excluido do Quintal."
      end

    end
  end
end