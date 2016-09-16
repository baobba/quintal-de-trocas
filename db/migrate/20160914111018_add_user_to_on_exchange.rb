class AddUserToOnExchange < ActiveRecord::Migration
  def change
    add_column :exchanges, :user_to, :integer
  end
end
