class Toy < ActiveRecord::Base

  acts_as_taggable
  acts_as_paranoid

  belongs_to :toy_category
  belongs_to :toy_age
  belongs_to :user
  has_many :toy_images, dependent: :destroy

  has_many :exchanges, :foreign_key => "toy_to"
  has_many :credits
  has_many :orders

  scope :actives, -> { where(is_active: true) }

  accepts_nested_attributes_for :toy_images, :allow_destroy => true, reject_if: :image_rejectable?

  validates :title, :description, :toy_category_id, :toy_age_id, presence: true

  paginates_per 30

  geocoded_by :zipcode
  after_validation :geocode
  after_save :set_featured_image
  after_save :check_active
  after_create :set_notification_date

  after_destroy      :remove_credit
  after_restore      :add_credit

  def set_notification_date
    update_attribute(:next_notification_at, Date.today + 2.months)
  end

  def add_credit
    self.user.credits.create(toy: self) if self.user && self.credits.count == 0
  end

  def remove_credit
    self.credits.available.first.update_column(:expired_at, Time.now) if self.credits.available.count > 0
  end

  def check_active
    if self.is_active_changed? && self.is_active
      self.add_credit
    elsif self.is_active_changed?
      self.remove_credit
    end
  end

  def set_featured_image
    if self.toy_images.where("image IS NOT NULL").count > 0
      self.toy_images.where("image IS NOT NULL").first.update_column(:featured, true) if !self.toy_images.where("image IS NOT NULL").map(&:featured).include? true
    end
  end

  def fet_image
    if toy_images.count > 0
      toy_images.featured.first || toy_images.first
    else
      toy_images.new
    end
  end

  def image_rejectable?(att)
    att['image'].blank? && new_record?
  end

  def to_param
    [id, title.parameterize].join("-")
  end

end
