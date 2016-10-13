class AddDeletedAtToExchanges < ActiveRecord::Migration
  def change
    add_column :exchanges, :deleted_at, :datetime
  end
end
