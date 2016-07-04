require 'test_helper'

class ToyImagesControllerTest < ActionController::TestCase
  setup do
    @toy_image = toy_images(:one)
  end

  test "should get index" do
    get :index
    assert_response :success
    assert_not_nil assigns(:toy_images)
  end

  test "should get new" do
    get :new
    assert_response :success
  end

  test "should create toy_image" do
    assert_difference('ToyImage.count') do
      post :create, toy_image: { image: @toy_image.image, toy_id: @toy_image.toy_id }
    end

    assert_redirected_to toy_image_path(assigns(:toy_image))
  end

  test "should show toy_image" do
    get :show, id: @toy_image
    assert_response :success
  end

  test "should get edit" do
    get :edit, id: @toy_image
    assert_response :success
  end

  test "should update toy_image" do
    patch :update, id: @toy_image, toy_image: { image: @toy_image.image, toy_id: @toy_image.toy_id }
    assert_redirected_to toy_image_path(assigns(:toy_image))
  end

  test "should destroy toy_image" do
    assert_difference('ToyImage.count', -1) do
      delete :destroy, id: @toy_image
    end

    assert_redirected_to toy_images_path
  end
end
