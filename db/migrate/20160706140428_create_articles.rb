class CreateArticles < ActiveRecord::Migration
  def change
    create_table :articles do |t|
      t.string :title
      t.text :body
      t.string :category
      t.datetime :published_at
      t.boolean :active
      t.string :cover
      t.references :user, index: true, foreign_key: true

      t.timestamps null: false
    end
  end
end
