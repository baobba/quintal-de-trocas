class AddReasonToExchange < ActiveRecord::Migration
  def change
    add_column :exchanges, :reason, :string
  end
end
