class ChangeDefaultValue2 < ActiveRecord::Migration
  def up
    change_column :exchanges, :accepted, :boolean, :null => true
  end

  def down
    change_column :exchanges, :accepted, :boolean, :null => false
  end
end
