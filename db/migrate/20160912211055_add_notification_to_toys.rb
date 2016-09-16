class AddNotificationToToys < ActiveRecord::Migration
  def change
    add_column :toys, :next_notification_at, :date
    add_column :toys, :expired_at, :datetime
  end
end
