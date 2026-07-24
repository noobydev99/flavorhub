// ─── FlavorHub API Module & LocalStorage Engine ────────────────

const USE_MOCK_API = false; // Set true to preserve frontend mock behavior for unimplemented endpoints.
const USE_BACKEND_AUTH = true; // Enable backend auth calls for login/register.
const USE_BACKEND_ORDERS = true; // Enable backend orders and order details.

// Dynamically compute API base URL by finding 'frontend' in the current path
// and treating its sibling 'api' folder as the API root.
// Works regardless of how deep the project is nested.
(function () {
  const parts = window.location.pathname.split('/');
  const frontendIdx = parts.lastIndexOf('frontend');
  const baseParts = frontendIdx >= 0 ? parts.slice(0, frontendIdx) : parts.slice(0, -1);
  window._API_BASE_URL = window.location.origin + baseParts.join('/') + '/api';
})();
const API_BASE_URL = window._API_BASE_URL || '../api';



// 1. Mock Menu Database
const MOCK_FOODS = [
  // Pizzas
  {
    id: "pizza-1",
    name: "Classic Pepperoni Pizza",
    category: "pizza",
    description: "Crispy crust topped with zesty pizza sauce, premium mozzarella cheese, and double layers of savory sliced pepperoni.",
    ingredients: "Wheat flour, tomato sauce, mozzarella cheese, pepperoni slices, oregano, olive oil.",
    price: 14.99,
    rating: 4.8,
    reviews: 124,
    image: "https://images.unsplash.com/photo-1628840042765-356cda07504e?auto=format&fit=crop&w=600&q=80"
  },
  {
    id: "pizza-2",
    name: "Margherita Supreme Pizza",
    category: "pizza",
    description: "An Italian classic featuring freshly pulled buffalo mozzarella, sweet vine-ripened tomatoes, fresh basil leaves, and a drizzle of extra virgin olive oil.",
    ingredients: "Hand-tossed dough, organic tomato sauce, buffalo mozzarella, fresh basil, cherry tomatoes, sea salt.",
    price: 12.99,
    rating: 4.7,
    reviews: 98,
    image: "https://images.unsplash.com/photo-1513104890138-7c749659a591?auto=format&fit=crop&w=600&q=80"
  },
  {
    id: "pizza-3",
    name: "Garden Veggie Pizza",
    category: "pizza",
    description: "Loaded with colorful bell peppers, red onions, mushrooms, black olives, sweet corn, and shredded mozzarella cheese on a thin crust.",
    ingredients: "Thin crust dough, tomato garlic sauce, mixed bell peppers, red onions, cremini mushrooms, black olives, mozzarella.",
    price: 13.99,
    rating: 4.5,
    reviews: 75,
    image: "https://images.unsplash.com/photo-1574071318508-1cdbab80d002?auto=format&fit=crop&w=600&q=80"
  },

  // Burgers
  {
    id: "burger-1",
    name: "FlavorHub Signature Burger",
    category: "burger",
    description: "Prime flame-grilled Angus beef patty topped with melted cheddar, crisp lettuce, heirloom tomato, caramelized onions, and our secret burger glaze on a toasted brioche bun.",
    ingredients: "Angus beef patty, brioche bun, cheddar cheese, lettuce, tomato, caramelized red onion, chef's secret glaze sauce.",
    price: 10.99,
    rating: 4.9,
    reviews: 215,
    image: "https://images.unsplash.com/photo-1568901346375-23c9450c58cd?auto=format&fit=crop&w=600&q=80"
  },
  {
    id: "burger-2",
    name: "Bacon Double Cheeseburger",
    category: "burger",
    description: "Two juicy smash patties, double layers of smoked cheddar cheese, crispy hickory bacon, pickles, and classic barbecue sauce.",
    ingredients: "Two beef patties, double cheddar cheese, applewood smoked bacon, dill pickles, hickory barbecue sauce.",
    price: 12.99,
    rating: 4.8,
    reviews: 164,
    image: "https://images.unsplash.com/photo-1550547660-d9450f859349?auto=format&fit=crop&w=600&q=80"
  },
  {
    id: "burger-3",
    name: "Spicy Buffalo Chicken Burger",
    category: "burger",
    description: "Crispy double-breaded chicken breast tossed in spicy buffalo sauce, topped with creamy blue cheese slaw and pickles on a sesame seed bun.",
    ingredients: "Battered chicken breast, buffalo sauce, blue cheese dressing, cabbage slaw, pickled slices, sesame bun.",
    price: 10.49,
    rating: 4.6,
    reviews: 110,
    image: "https://images.unsplash.com/photo-1525059696034-4967a8e1dca2?auto=format&fit=crop&w=600&q=80"
  },

  // Rice
  {
    id: "rice-1",
    name: "Classic Wok Fried Rice",
    category: "rice",
    description: "Fragrant jasmine rice stir-fried in a hot wok with egg, sweet green peas, diced carrots, spring onions, and premium light soy sauce.",
    ingredients: "Jasmine rice, scrambled eggs, green peas, carrots, spring onions, sesame oil, soy sauce.",
    price: 9.99,
    rating: 4.6,
    reviews: 82,
    image: "https://images.unsplash.com/photo-1512058564366-18510be2db19?auto=format&fit=crop&w=600&q=80"
  },
  {
    id: "rice-2",
    name: "Garlic Butter Shrimp Bowl",
    category: "rice",
    description: "Succulent prawns sauteed in rich garlic butter sauce, served over a bed of steamed jasmine rice with grilled asparagus and lemon wedge.",
    ingredients: "Sautéed shrimp, garlic, salted butter, jasmine rice, grilled asparagus, lemon herb garnish.",
    price: 13.99,
    rating: 4.8,
    reviews: 143,
    image: "https://images.unsplash.com/photo-1534422298391-e4f8c172dddb?auto=format&fit=crop&w=600&q=80"
  },
  {
    id: "rice-3",
    name: "Grilled Teriyaki Chicken Bowl",
    category: "rice",
    description: "Tender chicken thighs grilled to perfection and glazed with sweet teriyaki sauce, served over brown rice with broccoli, carrots, and sesame seed garnish.",
    ingredients: "Grilled chicken thighs, house-made teriyaki glaze, steamed brown rice, broccoli, carrot slices, sesame seeds.",
    price: 11.49,
    rating: 4.7,
    reviews: 95,
    image: "https://images.unsplash.com/photo-1546069901-ba9599a7e63c?auto=format&fit=crop&w=600&q=80"
  },

  // Drinks
  {
    id: "drink-1",
    name: "Fresh Strawberry Lemonade",
    category: "drinks",
    description: "A refreshing blend of freshly squeezed organic lemons, sweet strawberry puree, filtered water, and mint leaves served over crushed ice.",
    ingredients: "Fresh organic lemons, strawberry purée, cane sugar syrup, mint sprigs, crushed ice.",
    price: 3.99,
    rating: 4.7,
    reviews: 64,
    image: "https://images.unsplash.com/photo-1497534446932-c925b458314e?auto=format&fit=crop&w=600&q=80"
  },
  {
    id: "drink-2",
    name: "Iced Matcha Green Tea Latte",
    category: "drinks",
    description: "Authentic Japanese ceremonial-grade stone-ground matcha whisked into creamy oat milk and lightly sweetened with organic honey over ice.",
    ingredients: "Ceremonial matcha powder, honey, organic oat milk, ice cubes.",
    price: 4.99,
    rating: 4.8,
    reviews: 86,
    image: "https://images.unsplash.com/photo-1536256263959-770b48d82b0a?auto=format&fit=crop&w=600&q=80"
  },

  // Desserts
  {
    id: "dessert-1",
    name: "Decadent Chocolate Fudge Cake",
    category: "desserts",
    description: "Rich, moist multi-layered dark chocolate cake layered and coated with creamy chocolate fudge icing, served with a scoop of vanilla ice cream.",
    ingredients: "Dutch process cocoa, flour, sugar, butter, buttermilk, Belgian dark chocolate fudge icing, vanilla bean ice cream.",
    price: 5.99,
    rating: 4.9,
    reviews: 178,
    image: "https://images.unsplash.com/photo-1578985545062-69928b1d9587?auto=format&fit=crop&w=600&q=80"
  },
  {
    id: "dessert-2",
    name: "Classic New York Cheesecake",
    category: "desserts",
    description: "Velvety smooth baked cream cheese filling on a buttery graham cracker crust, topped with fresh raspberry compote.",
    ingredients: "Cream cheese, sour cream, eggs, sugar, graham cracker crumbs, fresh raspberries compote.",
    price: 6.49,
    rating: 4.7,
    reviews: 112,
    image: "https://images.unsplash.com/photo-1533134242443-d4fd215305ad?auto=format&fit=crop&w=600&q=80"
  }
];

const MOCK_FOOD_IDS = new Set(MOCK_FOODS.map(food => String(food.id)));

const sanitizeCartItems = (cart) => {
  if (!Array.isArray(cart)) return [];

  return cart.filter(item => {
    if (!item || typeof item !== "object") return false;
    if (item.id && MOCK_FOOD_IDS.has(String(item.id))) {
      return false;
    }
    return true;
  });
};

// Helper to initialize LocalStorage collections
const initDB = () => {
  if (!localStorage.getItem("flavorhub_users")) {
    localStorage.setItem("flavorhub_users", JSON.stringify([
      {
        fullName: "Test Customer",
        email: "customer@flavorhub.com",
        phone: "+1234567890",
        address: "123 Food Street, Tasty City",
        password: "password123"
      }
    ]));
  }

  const storedCart = localStorage.getItem("flavorhub_cart");
  if (!storedCart) {
    localStorage.setItem("flavorhub_cart", JSON.stringify([]));
  } else {
    try {
      const parsedCart = JSON.parse(storedCart);
      const sanitizedCart = sanitizeCartItems(parsedCart);
      if (sanitizedCart.length !== (Array.isArray(parsedCart) ? parsedCart.length : 0)) {
        localStorage.setItem("flavorhub_cart", JSON.stringify(sanitizedCart));
      }
    } catch (e) {
      localStorage.setItem("flavorhub_cart", JSON.stringify([]));
    }
  }

  if (!localStorage.getItem("flavorhub_orders")) {
    localStorage.setItem("flavorhub_orders", JSON.stringify([]));
  }
};
initDB();

// 2. Core API Engine Functions
const FlavorHubAPI = {
  // Utility
  normalizeRecipe: (raw) => {
    return {
      id: raw.id,
      name: raw.name,
      category: raw.category || "General",
      description: raw.description || "",
      ingredients: raw.ingredients || "",
      price: parseFloat(raw.price) || 0,
      rating: parseFloat(raw.rating) || 4.5,
      reviews: parseInt(raw.reviews) || 50,
      image: raw.image_url || raw.image || "https://images.unsplash.com/photo-1546069901-ba9599a7e63c?auto=format&fit=crop&w=600&q=80"
    };
  },
  // Menu Retrieval
  getMenu: async (category = "") => {
    if (!USE_MOCK_API) {
      try {
        const response = await fetch(`${API_BASE_URL}/menu.php` + (category ? `?category=${category}` : ""));
        const result = await response.json();
        if (Array.isArray(result)) {
          return result;
        }
        console.error("Unexpected menu API response:", result);
      } catch (e) {
        console.error("API Error, falling back to mock:", e);
      }
    }
    // Mock Response
    return category
      ? MOCK_FOODS.filter(food => food.category.toLowerCase() === category.toLowerCase())
      : MOCK_FOODS;
  },

  getFoodById: async (id) => {
    if (!USE_MOCK_API) {
      try {
        const response = await fetch(`${API_BASE_URL}/food_details.php?id=${id}`);
        return await response.json();
      } catch (e) {
        console.error("API Error, falling back to mock:", e);
      }
    }
    // Mock Response
    return MOCK_FOODS.find(food => food.id === id) || null;
  },

  // Admin Recipes Integration
  getRecipes: async (category = "") => {
    try {
      const response = await fetch(`${API_BASE_URL}/recipes.php` + (category ? `?category=${category}` : ""));
      const result = await response.json();
      if (Array.isArray(result)) {
        return result.map(recipe => FlavorHubAPI.normalizeRecipe(recipe));
      }
      console.error("Unexpected recipes API response:", result);
    } catch (e) {
      console.error("Recipes API Error:", e);
    }
    // Fallback to mock foods
    return category
      ? MOCK_FOODS.filter(food => food.category.toLowerCase() === category.toLowerCase())
      : MOCK_FOODS;
  },

  getRecipeById: async (id) => {
    try {
      const response = await fetch(`${API_BASE_URL}/recipe_details.php?id=${id}`);
      const result = await response.json();
      if (result && (result.id || result.id)) {
        return FlavorHubAPI.normalizeRecipe(result);
      }
      console.error("Unexpected recipe details response:", result);
    } catch (e) {
      console.error("Recipe Details API Error:", e);
    }
    return null;
  },

  getCategories: async () => {
    try {
      const response = await fetch(`${API_BASE_URL}/categories_list.php`);
      const result = await response.json();
      if (Array.isArray(result)) {
        return result;
      }
      console.error("Unexpected categories API response:", result);
    } catch (e) {
      console.error("Categories API Error:", e);
    }
    // Fallback to mock categories from foods
    const categories = new Set(MOCK_FOODS.map(f => f.category));
    return Array.from(categories).map(name => ({ id: name, name }));
  },

  // Convert recipe from admin format to frontend format
  normalizeRecipe: (recipe) => {
    // Build correct image URL: DB stores relative paths like 'uploads/file.jpg'
    // frontend pages are in /frontend/, so we need '../uploads/file.jpg'
    let imageUrl = recipe.image_url || '';
    if (imageUrl && !imageUrl.startsWith('http') && !imageUrl.startsWith('../')) {
      imageUrl = '../' + imageUrl;
    }
    if (!imageUrl) {
      imageUrl = 'https://images.unsplash.com/photo-1495548014529-d037105e79f0?auto=format&fit=crop&w=600&q=80';
    }

    const normalized = {
      id: recipe.id,
      name: recipe.title || recipe.name,
      category: recipe.category_name || recipe.category || 'recipe',
      description: recipe.description || '',
      ingredients: recipe.ingredients || '',
      price: parseFloat(recipe.price) || 0.00, // Read directly from db
      rating: parseFloat(recipe.rating) || 4.5,
      reviews: recipe.reviews || 0,
      image: imageUrl
    };

    // Cache this item so addToCart can find it later
    FlavorHubAPI._backendItemsCache[String(normalized.id)] = normalized;
    return normalized;
  },

  // Cache for backend recipe items so addToCart can look them up
  _backendItemsCache: {},

  // Cart Management
  getCart: () => {
    try {
      const storedCart = JSON.parse(localStorage.getItem("flavorhub_cart")) || [];
      return sanitizeCartItems(storedCart);
    } catch (e) {
      return [];
    }
  },

  saveCart: (cart) => {
    const sanitizedCart = sanitizeCartItems(cart);
    localStorage.setItem("flavorhub_cart", JSON.stringify(sanitizedCart));
    // Emit global event to update cart icons on all screens
    window.dispatchEvent(new Event("cartUpdated"));
  },

  addToCart: (foodId, qty = 1) => {
    const cart = FlavorHubAPI.getCart();
    // Look up the item: first check backend cache, then fall back to MOCK_FOODS
    const foodItem = FlavorHubAPI._backendItemsCache[String(foodId)]
      || MOCK_FOODS.find(f => f.id === foodId);
    if (!foodItem) return false;

    const existingIndex = cart.findIndex(item => String(item.id) === String(foodId));
    if (existingIndex > -1) {
      cart[existingIndex].quantity += qty;
    } else {
      cart.push({
        id: foodItem.id,
        name: foodItem.name,
        price: foodItem.price,
        image: foodItem.image,
        quantity: qty
      });
    }
    FlavorHubAPI.saveCart(cart);
    return true;
  },

  buyNow: (foodId, qty = 1) => {
    if (FlavorHubAPI.addToCart(foodId, qty)) {
      window.location.href = 'checkout.html';
    }
  },

  updateCartQty: (foodId, qty) => {
    let cart = FlavorHubAPI.getCart();
    const idx = cart.findIndex(item => String(item.id) === String(foodId));
    if (idx > -1) {
      if (qty <= 0) {
        cart.splice(idx, 1);
      } else {
        cart[idx].quantity = qty;
      }
      FlavorHubAPI.saveCart(cart);
      return true;
    }
    return false;
  },

  removeFromCart: (foodId) => {
    let cart = FlavorHubAPI.getCart();
    cart = cart.filter(item => String(item.id) !== String(foodId));
    FlavorHubAPI.saveCart(cart);
  },

  clearCart: () => {
    FlavorHubAPI.saveCart([]);
  },

  // Customer Authentication
  getCurrentUser: () => {
    return JSON.parse(localStorage.getItem("flavorhub_user")) || null;
  },

  login: async (email, password) => {
    if (USE_BACKEND_AUTH) {
      try {
        const response = await fetch(`${API_BASE_URL}/login.php`, {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({ email, password })
        });
        const result = await response.json();
        if (result.success) {
          localStorage.setItem("flavorhub_user", JSON.stringify(result.user));
          return { success: true };
        }
        return { success: false, message: result.message };
      } catch (e) {
        console.error("Backend login error, falling back to mock:", e);
      }
    }

    // Mock login check
    const users = JSON.parse(localStorage.getItem("flavorhub_users")) || [];
    const matched = users.find(u => u.email.toLowerCase() === email.toLowerCase() && u.password === password);
    if (matched) {
      const userSession = { ...matched };
      delete userSession.password;
      localStorage.setItem("flavorhub_user", JSON.stringify(userSession));
      return { success: true };
    }
    return { success: false, message: "Invalid email address or password." };
  },

  register: async (fullName, email, phone, address, password) => {
    if (USE_BACKEND_AUTH) {
      try {
        const response = await fetch(`${API_BASE_URL}/register.php`, {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({ fullName, email, phone, address, password })
        });
        const result = await response.json();
        if (result.success) {
          localStorage.setItem("flavorhub_user", JSON.stringify(result.user));
        }
        return result;
      } catch (e) {
        console.error("Backend registration error, falling back to mock:", e);
      }
    }

    const users = JSON.parse(localStorage.getItem("flavorhub_users")) || [];
    if (users.some(u => u.email.toLowerCase() === email.toLowerCase())) {
      return { success: false, message: "An account with this email already exists." };
    }

    const newUser = { fullName, email, phone, address, password };
    users.push(newUser);
    localStorage.setItem("flavorhub_users", JSON.stringify(users));

    const userSession = { ...newUser };
    delete userSession.password;
    localStorage.setItem("flavorhub_user", JSON.stringify(userSession));

    return { success: true };
  },

  logout: () => {
    localStorage.removeItem("flavorhub_user");
    window.location.href = "index.html";
  },

  updateProfile: async (updatedUser) => {
    if (USE_BACKEND_AUTH) {
      try {
        const response = await fetch(`${API_BASE_URL}/update_profile.php`, {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify(updatedUser)
        });
        const result = await response.json();
        if (result.success) {
          const normalizedUser = {
            ...result.user,
            fullName: result.user.fullName ?? result.user.fullname,
            email: result.user.email,
            phone: result.user.phone || "",
            address: result.user.address || ""
          };
          localStorage.setItem("flavorhub_user", JSON.stringify(normalizedUser));
          return true;
        }
        console.error("Profile update failed:", result.message);
      } catch (e) {
        console.error("Backend profile update error:", e);
      }
    }

    // Local fallback
    localStorage.setItem("flavorhub_user", JSON.stringify(updatedUser));
    const users = JSON.parse(localStorage.getItem("flavorhub_users")) || [];
    const idx = users.findIndex(u => u.email.toLowerCase() === updatedUser.email.toLowerCase());
    if (idx > -1) {
      users[idx] = { ...users[idx], ...updatedUser };
      localStorage.setItem("flavorhub_users", JSON.stringify(users));
    }
    return true;
  },

  // Order Placement and Pipeline
  placeOrder: async (deliveryDetails) => {
    const cart = FlavorHubAPI.getCart();
    if (cart.length === 0) return { success: false, message: "Your shopping cart is empty." };

    const user = FlavorHubAPI.getCurrentUser();
    if (!user) return { success: false, message: "Please log in to place an order." };

    const subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    const tax = 0;
    const deliveryFee = 300;
    const total = subtotal + tax + deliveryFee;

    const orderId = "ORD-" + Math.floor(100000 + Math.random() * 900000);

    const newOrder = {
      orderId,
      customerEmail: user.email,
      date: new Date().toISOString(),
      items: cart,
      subtotal,
      tax,
      deliveryFee,
      total,
      deliveryDetails,
      status: "Order Received", // received, preparing, out_for_delivery, delivered
      estimatedTime: "25-35 mins",
      progress: 15 // Percent
    };

    if (!USE_MOCK_API) {
      try {
        const response = await fetch(`${API_BASE_URL}/place_order.php`, {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify(newOrder)
        });
        const result = await response.json();
        if (result.success) {
          FlavorHubAPI.clearCart();
          return { success: true, orderId: result.orderId };
        }
      } catch (e) {
        console.error("API error placing order:", e);
      }
    }

    // Save Mock order
    const orders = JSON.parse(localStorage.getItem("flavorhub_orders")) || [];
    orders.unshift(newOrder);
    localStorage.setItem("flavorhub_orders", JSON.stringify(orders));

    FlavorHubAPI.clearCart();
    return { success: true, orderId };
  },

  normalizeOrder: (order) => {
    if (!order) return null;

    const normalized = {
      ...order,
      orderId: order.orderId || order.order_id || "",
      date: order.created_at || order.date || new Date().toISOString(),
      estimatedTime: order.estimated_time || order.estimatedTime || "",
      customerEmail: order.customer_email || order.customerEmail || "",
      deliveryDetails: order.deliveryDetails || {
        fullName: order.customer_name || "",
        phone: order.customer_phone || "",
        address: order.customer_address || "",
        paymentMethod: order.payment_method || "",
        instructions: order.special_instructions || ""
      },
      items: (order.items || []).map(item => ({
        id: item.id || item.food_id || "",
        name: item.name || "",
        price: item.price || item.unit_price || 0,
        quantity: item.quantity || 0,
        total: parseFloat(item.total_price || (item.price || item.unit_price || 0) * (item.quantity || 0))
      })),
      status: order.status || "Order Received",
    };

    if (normalized.total === undefined || normalized.total === null || isNaN(parseFloat(normalized.total))) {
      normalized.total = normalized.items.reduce((sum, item) => sum + parseFloat(item.total || 0), 0);
    } else {
      normalized.total = parseFloat(normalized.total);
    }

    const progressMap = {
      "Order Received": 15,
      "Preparing": 45,
      "Out for Delivery": 75,
      "Delivered": 100
    };
    normalized.progress = progressMap[normalized.status] || 15;

    return normalized;
  },

  getOrders: async () => {
    const user = FlavorHubAPI.getCurrentUser();
    if (!user) return [];

    if (USE_BACKEND_ORDERS) {
      try {
        const response = await fetch(`${API_BASE_URL}/orders.php?user_id=${encodeURIComponent(user.id)}`);
        const orders = await response.json();
        if (Array.isArray(orders)) {
          return orders.map(FlavorHubAPI.normalizeOrder);
        }
      } catch (e) {
        console.error("Backend orders fetch failed:", e);
      }
    }

    FlavorHubAPI.advanceOrdersStatus();
    const orders = JSON.parse(localStorage.getItem("flavorhub_orders")) || [];
    return orders
      .filter(o => o.customerEmail === user.email)
      .map(FlavorHubAPI.normalizeOrder);
  },

  getOrderById: async (orderId) => {
    if (USE_BACKEND_ORDERS) {
      try {
        const response = await fetch(`${API_BASE_URL}/order_details.php?orderId=${encodeURIComponent(orderId)}`);
        const result = await response.json();
        if (result && (result.order_id || result.orderId)) {
          return FlavorHubAPI.normalizeOrder(result);
        }
      } catch (e) {
        console.error("Backend order details fetch failed:", e);
      }
    }

    FlavorHubAPI.advanceOrdersStatus();
    const orders = JSON.parse(localStorage.getItem("flavorhub_orders")) || [];
    return FlavorHubAPI.normalizeOrder(orders.find(o => o.orderId === orderId) || null);
  },

  // Status simulation: Advances orders status based on time elapsed
  advanceOrdersStatus: () => {
    const orders = JSON.parse(localStorage.getItem("flavorhub_orders")) || [];
    let updated = false;

    const now = new Date().getTime();
    orders.forEach(o => {
      const placedTime = new Date(o.date).getTime();
      const elapsedMinutes = (now - placedTime) / 60000; // minutes

      let currentStatus = o.status;
      let currentProgress = o.progress;

      if (elapsedMinutes >= 3) {
        currentStatus = "Delivered";
        currentProgress = 100;
      } else if (elapsedMinutes >= 2) {
        currentStatus = "Out for Delivery";
        currentProgress = 75;
      } else if (elapsedMinutes >= 1) {
        currentStatus = "Preparing";
        currentProgress = 45;
      }

      if (o.status !== currentStatus) {
        o.status = currentStatus;
        o.progress = currentProgress;
        updated = true;
      }
    });

    if (updated) {
      localStorage.setItem("flavorhub_orders", JSON.stringify(orders));
    }
  }
};
window.FlavorHubAPI = FlavorHubAPI;

