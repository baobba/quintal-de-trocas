class AddisAcquiredToCredits < ActiveRecord::Migration
  def change
    add_column :credits, :used_in_exchange_id, :integer
  end
end
