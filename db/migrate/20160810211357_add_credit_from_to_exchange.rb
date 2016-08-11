class AddCreditFromToExchange < ActiveRecord::Migration

  def up
    add_column :exchanges, :credit_offer, :boolean
    add_column :credits, :expired_at, :datetime, :default => nil
    # remove_column :exchanges, :is_available
  end

  def down
    remove_column :exchanges, :credit_offer
    rename_column :credits, :expired_at
    # add_column :exchanges, :is_available, :boolean
  end
end
