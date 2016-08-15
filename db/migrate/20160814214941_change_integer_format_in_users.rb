class ChangeIntegerFormatInUsers < ActiveRecord::Migration
  def up
    change_column :users, :birthday, :string
  end

  def down
    change_column :users, :birthday, :integer
  end
end
