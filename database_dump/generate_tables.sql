create table products
(
    id            serial
        primary key,
    name          varchar(255)               not null
        unique,
    carbohydrates double precision default 0 not null,
    fats          double precision default 0 not null,
    protein       double precision default 0 not null,
    fibre         double precision default 0 not null,
    kcal          double precision default 0 not null
);

alter table products
    owner to root;

create table meals
(
    id         serial
        primary key,
    name       varchar(255) not null
        unique,
    created_at timestamp default CURRENT_TIMESTAMP
);

alter table meals
    owner to root;

create table meal_products
(
    id         serial
        primary key,
    meal_id    integer                    not null
        references meals
            on delete cascade,
    product_id integer                    not null
        references products
            on delete cascade,
    quantity   double precision default 1 not null
);

alter table meal_products
    owner to root;

create table user_roles
(
    id   serial
        primary key,
    role varchar(50) not null
        unique
);

alter table user_roles
    owner to root;

create table user_details
(
    id           serial
        primary key,
    name         varchar(100) not null,
    surname      varchar(100) not null,
    phone_number varchar(20)  not null,
    user_role_id integer
        constraint fk_user_role
            references user_roles
            on delete set null
);

alter table user_details
    owner to root;

create table users
(
    id              serial
        primary key,
    id_user_details integer                 not null
        unique
        constraint fk_user_details
            references user_details
            on delete cascade,
    email           varchar(255)            not null
        unique,
    password        varchar(255)            not null,
    enabled         boolean   default false not null,
    created_at      timestamp default CURRENT_TIMESTAMP
);

alter table users
    owner to root;

create table user_meals
(
    id          serial
        primary key,
    user_id     integer not null
        references users
            on delete cascade,
    meal_id     integer not null
        references meals
            on delete cascade,
    consumed_at timestamp default CURRENT_TIMESTAMP
);

alter table user_meals
    owner to root;

create function calculate_kcal_function() returns trigger
    language plpgsql
as
$$
BEGIN
    -- kcal formula: (fats * 9) + (protein * 4) + (carbohydrates * 4)
    NEW.kcal := (NEW.fats * 9) + (NEW.protein * 4) + (NEW.carbohydrates * 4);
    RETURN NEW;
END;
$$;

alter function calculate_kcal_function() owner to root;

create trigger calculate_kcal_trigger
    before insert or update
    on products
    for each row
execute procedure calculate_kcal_function();

