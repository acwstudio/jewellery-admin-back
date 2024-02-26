WITH RECURSIVE CategoryTree
                   (id, parent_id, name, slug, depth)
                   AS (
        SELECT id, parent_id, name, slug, 0 AS depth
        FROM product_categories
        WHERE parent_id IS NULL
        UNION ALL
        SELECT c.id, c.parent_id, c.name, c.slug, ct.depth+1 AS depth
        FROM CategoryTree ct
                 JOIN product_categories c ON (c.parent_id = ct.id)
    )
SELECT * FROM CategoryTree;
