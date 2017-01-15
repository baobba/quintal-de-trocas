class RenameToyToItem < ActiveRecord::Migration
  def self.up
    # rename columns
    rename_column :toys, :toy_category_id, :item_category_id
    rename_column :toys, :toy_age_id, :item_age_id
    rename_column :exchanges, :toy_from, :item_from
    rename_column :exchanges, :toy_to, :item_to
    rename_column :credits, :toy_id, :item_id
    rename_column :toy_images, :toy_id, :item_id
    rename_column :orders, :toy_id, :item_id

    # rename tables
    rename_table :toys, :items
    rename_table :toy_ages, :item_ages
    rename_table :toy_categories, :item_categories
    rename_table :toy_images, :item_images
  end

  def self.down
    # rename columns
    rename_column :items, :item_category_id, :toy_category_id
    rename_column :items, :item_age_id, :toy_age_id
    rename_column :exchanges, :item_from, :toy_from
    rename_column :exchanges, :item_to, :toy_to
    rename_column :credits, :item_id, :toy_id
    rename_column :item_images, :item_id, :toy_id
    rename_column :orders, :item_id, :toy_id

    # rename tables
    rename_table :items, :toys
    rename_table :item_ages, :toy_ages
    rename_table :item_categories, :toy_categories
    rename_table :item_images, :toy_images
  end
end
