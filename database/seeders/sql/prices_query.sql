select s.id as size_id, cp.price as value, cp.price_category, s.is_active
from products p
    join sizes s on s.product_id = p.id
    join core_prices cp on p.core_id = cp.core_id;
