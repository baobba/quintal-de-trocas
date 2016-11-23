class ChangeDefaultValue4 < ActiveRecord::Migration
  def up
    change_column_null :exchanges, :accepted, true
    change_column_default :exchanges, :accepted, nil
  end

  def down
    change_column_null :exchanges, :accepted, false
    change_column_default :exchanges, :accepted, false
  end
end
