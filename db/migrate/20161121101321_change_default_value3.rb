class ChangeDefaultValue3 < ActiveRecord::Migration
  def up
    change_column_null(:exchanges, :accepted, true)
  end

  def down
    change_column_null(:exchanges, :accepted, true)
  end
end
