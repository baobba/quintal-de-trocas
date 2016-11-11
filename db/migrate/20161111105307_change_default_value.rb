class ChangeDefaultValue < ActiveRecord::Migration
  def up
    change_column_default(:exchanges, :accepted, nil)
  end

  def up
    change_column_default(:exchanges, :accepted, false)
  end
end
