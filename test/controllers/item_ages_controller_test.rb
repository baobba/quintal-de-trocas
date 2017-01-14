require 'test_helper'

class ItemAgesControllerTest < ActionController::TestCase
  setup do
    @item_age = item_ages(:one)
  end

  test "should get index" do
    get :index
    assert_response :success
    assert_not_nil assigns(:item_ages)
  end

  test "should get new" do
    get :new
    assert_response :success
  end

  test "should create item_age" do
    assert_difference('itemAge.count') do
      post :create, item_age: { title: @item_age.title }
    end

    assert_redirected_to item_age_path(assigns(:item_age))
  end

  test "should show item_age" do
    get :show, id: @item_age
    assert_response :success
  end

  test "should get edit" do
    get :edit, id: @item_age
    assert_response :success
  end

  test "should update item_age" do
    patch :update, id: @item_age, item_age: { title: @item_age.title }
    assert_redirected_to item_age_path(assigns(:item_age))
  end

  test "should destroy item_age" do
    assert_difference('itemAge.count', -1) do
      delete :destroy, id: @item_age
    end

    assert_redirected_to item_ages_path
  end
end
