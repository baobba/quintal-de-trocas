require 'test_helper'

class ItemCategoriesControllerTest < ActionController::TestCase
  setup do
    @item_category = item_categories(:one)
  end

  test "should get index" do
    get :index
    assert_response :success
    assert_not_nil assigns(:item_categories)
  end

  test "should get new" do
    get :new
    assert_response :success
  end

  test "should create item_category" do
    assert_difference('itemCategory.count') do
      post :create, item_category: { title: @item_category.title }
    end

    assert_redirected_to item_category_path(assigns(:item_category))
  end

  test "should show item_category" do
    get :show, id: @item_category
    assert_response :success
  end

  test "should get edit" do
    get :edit, id: @item_category
    assert_response :success
  end

  test "should update item_category" do
    patch :update, id: @item_category, item_category: { title: @item_category.title }
    assert_redirected_to item_category_path(assigns(:item_category))
  end

  test "should destroy item_category" do
    assert_difference('itemCategory.count', -1) do
      delete :destroy, id: @item_category
    end

    assert_redirected_to item_categories_path
  end
end
