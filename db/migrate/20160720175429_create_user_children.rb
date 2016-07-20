class CreateUserChildren < ActiveRecord::Migration
  def change
    create_table :user_children do |t|
      t.string :name
      t.string :birthday
      t.references :user, index: true, foreign_key: true
    end
  end
end
