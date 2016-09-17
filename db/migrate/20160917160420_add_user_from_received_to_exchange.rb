class AddUserFromReceivedToExchange < ActiveRecord::Migration
  def change
    add_column :exchanges, :user_from_received, :datetime
    add_column :exchanges, :user_to_received, :datetime
  end
end
