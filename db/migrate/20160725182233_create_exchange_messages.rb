class CreateExchangeMessages < ActiveRecord::Migration
  def change
    create_table :exchange_messages do |t|
      t.references :user, index: true, foreign_key: true
      t.references :exchange, index: true, foreign_key: true
      t.integer :user_from
      t.integer :user_to
      t.text :message
      t.datetime :read_at

      t.timestamps null: false
    end
  end
end
