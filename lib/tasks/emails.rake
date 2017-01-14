#encoding: utf-8 

namespace :emails do
  
  task :check_your_items, [:limit] => [:environment] do |t, args|
    limit = args[:limit] || nil

    User.order("id DESC").limit(limit).each_with_index do |user|
      if !user.email.blank?
        QuintalMailer.check_your_items(user).deliver_now rescue next
      end
      sleep(1)
    end
  end
  
  task :support_quintal, [:limit] => [:environment] do |t, args|
    limit = args[:limit] || nil

    User.order("id DESC").each_with_index do |user|
      if !user.email.blank?
        QuintalMailer.support_quintal(user).deliver_now rescue next
      end
      sleep(1)
    end
  end
  
  task :update_profile, [:limit] => [:environment] do |t, args|
    limit = args[:limit] || nil

    User.order("id DESC").each_with_index do |user|
      if !user.email.blank?
        QuintalMailer.update_profile(user).deliver_now rescue next
      end
      sleep(1)
    end
  end

end