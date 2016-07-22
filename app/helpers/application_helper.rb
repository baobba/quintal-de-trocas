module ApplicationHelper

  
  def bootstrap_class_for flash_type
    { success: "alert-success", error: "alert-danger", alert: "alert-warning", notice: "alert-info" }[flash_type.to_sym] || flash_type.to_s
  end

  def flash_messages(opts = {})
    flash.each do |msg_type, message|
      concat(content_tag(:div, message, class: "alert #{bootstrap_class_for(msg_type)} fade in") do 
              concat content_tag(:button, 'x', class: "close", data: { dismiss: 'alert' })
              concat message 
            end)
    end
    nil
  end

  def default_img(user)
    if !user.avatar?
      if (user.gender == "M")
        image_url "fallback/male.png"
      else
        image_url "fallback/female.png"
      end
    else
      user.avatar_url
    end
  end
  
end
