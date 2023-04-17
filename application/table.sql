CREATE TABLE `gl_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT  COMMENT '用户ID',
  `username` varchar(20) NOT NULL DEFAULT '' COMMENT '用户名',
  `integral` int(11) NOT NULL DEFAULT 0 COMMENT '积分数量',
  `recomend_user_id` int(11) NOT NULL DEFAULT 0 COMMENT '推荐人ID',
  `adddate` datetime NOT NULL COMMENT '添加时间',
  `update` timestamp NOT NULL default CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT '用户表';

CREATE TABLE `gl_user_change_integrals` (
  `id` int(11) NOT NULL AUTO_INCREMENT  COMMENT '用户ID',
  `change_type` tinyint(2) NOT NULL DEFAULT 1 COMMENT '变动类型 1 增加 2减少',
  `change_integral` int(11) NOT NULL DEFAULT 0 COMMENT '变动的积分数量',
  `user_id` int(11) NOT NULL DEFAULT 0 COMMENT '发生积分变动的用户ID',
  `contri_user_id` int(11) NOT NULL DEFAULT 0 COMMENT '贡献此次变动的用户ID',
  `content` varchar(255) NOT NULL DEFAULT '' COMMENT '变动描述',
  `adddate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT '用户积分变动记录表';


CREATE TABLE `gl_integral_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT  COMMENT '配置ID',
  `first_integral` int(11) NOT NULL DEFAULT 0 COMMENT '直接推荐人赠送积分数量',
  `second_integral` int(11) NOT NULL DEFAULT 0 COMMENT '二级推荐人赠送积分数量',
  `status` tinyint(2) NOT NULL DEFAULT 1 COMMENT '是否可用 1可用 2不可用',
  `adddate` datetime NOT NULL COMMENT '添加时间',
  `update` timestamp NOT NULL default CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT '积分赠送配置表';