every 1.day, :at => '9:00 am', :roles => [:app] do
  rake "quintal:send_toys_reminder"
end