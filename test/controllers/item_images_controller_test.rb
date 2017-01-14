require 'test_helper'

class ItemImagesControllerTest < ActionController::TestCase
  setup do
    @item_image = item_images(:one)
  end

  test "should get index" do
    get :index
    assert_response :success
    assert_not_nil assigns(:item_images)
  end

  test "should get new" do
    get :new
    assert_response :success
  end

  test "should create item_image" do
    assert_difference('itemImage.count') do
      post :create, item_image: { image: @item_image.image, item_id: @item_image.item_id }
    end

    assert_redirected_to item_image_path(assigns(:item_image))
  end

  test "should show item_image" do
    get :show, id: @item_image
    assert_response :success
  end

  test "should get edit" do
    get :edit, id: @item_image
    assert_response :success
  end

  test "should update item_image" do
    patch :update, id: @item_image, item_image: { image: @item_image.image, item_id: @item_image.item_id }
    assert_redirected_to item_image_path(assigns(:item_image))
  end

  test "should destroy item_image" do
    assert_difference('itemImage.count', -1) do
      delete :destroy, id: @item_image
    end

    assert_redirected_to item_images_path
  end
end
