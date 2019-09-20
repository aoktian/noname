insert into pros(name) values('默认');
insert into tags(name, pro) values('默认', 1);

insert into titles(id, name, caty, locked) values
(100, '需求', 2, 1),
(101, '改进', 2, 1),
(102, 'BUG', 2, 1),
(300, '管理', 1, 1),
(301, '策划', 1, 1),
(302, '美术', 1, 1),
(303, '程序', 1, 1),
(305, 'QA', 1, 1),
(306, '其他', 1, 1);

insert into users(id, email, name, password, department) values
(10001, 'abc@abc.com', '娃哈哈', '$2y$10$JO82lIGa8oVgWFHkFw9N3uFW/9ZEX7m/TXh2tUYHKo25cNyKvuB1W', 300);
