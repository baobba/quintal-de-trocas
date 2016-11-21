class Item < ActiveRecord::Base

  acts_as_taggable
  acts_as_paranoid

  scope :for_exc, -> { where("price IS NULL") }
  scope :for_buy, -> { where("price IS NOT NULL") }

  belongs_to :item_category
  belongs_to :item_age
  belongs_to :user
  has_many :item_images, dependent: :destroy

  has_many :exchanges, :foreign_key => "item_to"
  has_many :credits
  has_many :orders

  accepts_nested_attributes_for :item_images, :allow_destroy => true, reject_if: :image_rejectable?

  validates :title, :description, :item_category_id, :item_age_id, presence: true

  paginates_per 30

  geocoded_by :zipcode
  after_validation :geocode
  after_save :set_featured_image
  after_create :set_notification_date

  after_destroy      :remove_credit
  after_restore      :add_credit

  def for_sale?
    price.blank? ? false : true
  end

  def set_notification_date
    update_attribute(:next_notification_at, Date.today + 2.months)
    self.user.credits.create(item: self) if self.user
  end

  def add_credit
    self.credits.create(item: self)
  end

  def remove_credit
    self.credits.available.first.update_column(:expired_at, Time.now) if self.credits.available.count > 0
  end

  def set_featured_image
    if self.item_images.where("image IS NOT NULL").count > 0
      self.item_images.where("image IS NOT NULL").first.update_column(:featured, true) if !self.item_images.where("image IS NOT NULL").map(&:featured).include? true
    end
  end

  def fet_image
    if item_images.count > 0
      item_images.featured.first || item_images.first
    else
      item_images.new
    end
  end

  def image_rejectable?(att)
    att['image'].blank? && new_record?
  end

  def to_param
    [id, title.parameterize].join("-")
  end

end
