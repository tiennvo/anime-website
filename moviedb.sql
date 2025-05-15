-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Máy chủ: localhost:3306
-- Thời gian đã tạo: Th5 16, 2025 lúc 02:50 AM
-- Phiên bản máy phục vụ: 10.11.11-MariaDB-log
-- Phiên bản PHP: 8.3.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `tiennvon_anime`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `bank_auto`
--

CREATE TABLE `bank_auto` (
  `id` int(11) NOT NULL,
  `tid` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `amount` bigint(20) NOT NULL,
  `time` datetime DEFAULT current_timestamp(),
  `username` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci;

--
-- Đang đổ dữ liệu cho bảng `bank_auto`
--

INSERT INTO `bank_auto` (`id`, `tid`, `description`, `amount`, `time`, `username`) VALUES
(1, '055zb76-806M7uxhX', 'REM Tfr Ac:1770260769 O@L_080005_211601_0_0_27700916_TIENNVONET_VO TRAN TIEN__87241845254 0344916249 nt 7', 10000, '2025-05-13 03:32:25', 'tiennvo'),
(2, '0552N7Q6-808bflW0K', 'REM Tfr Ac:1770260769 O@L_080005_211601_0_0_32100598_TIENNVONET_VO TRAN TIEN__87394218875 0344916249 nt 7', 10000, '2025-05-14 15:08:08', 'tiennvo');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `buy_movies`
--

CREATE TABLE `buy_movies` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `movie_id` int(11) NOT NULL,
  `saved_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `buy_movies`
--

INSERT INTO `buy_movies` (`id`, `user_id`, `movie_id`, `saved_at`) VALUES
(9, 7, 7, '2025-04-25 19:39:14');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `movie_id` int(11) DEFAULT NULL,
  `comment` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `comments`
--

INSERT INTO `comments` (`id`, `user_id`, `movie_id`, `comment`, `created_at`) VALUES
(4, 4, 11, 'phim đỉnh vậy anh zai', '2024-08-28 15:07:15'),
(5, 2, 6, 'phim hay', '2025-04-24 04:30:41'),
(6, 2, 11, 'phim hay đấy bro', '2025-04-24 07:17:14'),
(7, 7, 11, 'phim hay', '2025-04-25 06:40:45');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `countries`
--

CREATE TABLE `countries` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `countries`
--

INSERT INTO `countries` (`id`, `name`) VALUES
(4, 'Nhật Bản'),
(5, 'Trung Quốc'),
(3, 'Việt Nam');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `dongtien`
--

CREATE TABLE `dongtien` (
  `id` int(11) NOT NULL,
  `sotientruoc` bigint(20) NOT NULL,
  `sotienthaydoi` bigint(20) NOT NULL,
  `sotiensau` bigint(20) NOT NULL,
  `thoigian` datetime DEFAULT current_timestamp(),
  `noidung` text DEFAULT NULL,
  `username` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci;

--
-- Đang đổ dữ liệu cho bảng `dongtien`
--

INSERT INTO `dongtien` (`id`, `sotientruoc`, `sotienthaydoi`, `sotiensau`, `thoigian`, `noidung`, `username`) VALUES
(1, 999, 10000, 10999, '2025-05-13 03:32:25', 'BIDV | 055zb76-806M7uxhX', 'tiennvo'),
(2, 10999, 10000, 20999, '2025-05-14 15:08:08', 'BIDV | 0552N7Q6-808bflW0K', 'tiennvo'),
(3, 20999, 1231, 19768, '2025-05-16 02:44:05', 'Mua phim:  Phong Khởi Lạc Dương: Song Tử Truy Hung', 'tiennvo');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `episodes`
--

CREATE TABLE `episodes` (
  `id` int(11) NOT NULL,
  `movie_id` int(11) DEFAULT NULL,
  `episode_number` int(11) NOT NULL,
  `video_url` varchar(255) DEFAULT NULL,
  `views` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `episodes`
--

INSERT INTO `episodes` (`id`, `movie_id`, `episode_number`, `video_url`, `views`) VALUES
(12, 6, 1, '//ok.ru/videoembed/9002443344419?nochat=1', 5),
(13, 7, 1, '//ok.ru/videoembed/6663194020603', 10),
(14, 6, 2, '//ok.ru/videoembed/9002443344419?nochat=1', 2),
(15, 23, 1, '//ok.ru/videoembed/7401068235403', 0),
(16, 7, 2, '//ok.ru/videoembed/7296708184795', 10),
(17, 11, 1, '//ok.ru/videoembed/7301133306587', 15),
(18, 11, 2, '//ok.ru/videoembed/7105384614651', 12),
(19, 24, 1, '//ok.ru/videoembed/8701165963915?nochat=1', 2);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `genres`
--

CREATE TABLE `genres` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `genres`
--

INSERT INTO `genres` (`id`, `name`) VALUES
(4, 'Anime'),
(10, 'CN Animation'),
(8, 'Cổ Trang'),
(7, 'Hình Sự'),
(3, 'Kinh Dị'),
(6, 'Tâm Lý'),
(5, 'Tình Cảm'),
(12, 'Viễn Tưởng'),
(11, 'Võ Thuật'),
(9, 'Xuyên Không');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `movies`
--

CREATE TABLE `movies` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `release_year` int(11) DEFAULT NULL,
  `director` varchar(100) DEFAULT NULL,
  `actors` text DEFAULT NULL,
  `genre` varchar(100) DEFAULT NULL,
  `rating` float DEFAULT 0,
  `trailer_url` varchar(255) DEFAULT NULL,
  `type` enum('series','movie') NOT NULL,
  `video_url` varchar(255) DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `country_id` int(11) DEFAULT NULL,
  `total_views` int(255) NOT NULL,
  `money` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `movies`
--

INSERT INTO `movies` (`id`, `title`, `description`, `release_year`, `director`, `actors`, `genre`, `rating`, `trailer_url`, `type`, `video_url`, `image_url`, `country_id`, `total_views`, `money`) VALUES
(6, 'THẦN ẤN VƯƠNG TOẠ (PHẦN 1)', 'Thần Ấn Vương Toạ (Phần 1) Vào lúc loài người sắp diệt vong, Lục Đại Thánh Điện nổi lên, dẫn dắt loài người bảo vệ lãnh thổ cuối cùng. Một thiếu niên vì cứu mẹ mà gia nhập Kỵ Sĩ Thánh Điện, kỳ tích, quỷ kế không ngừng diễn ra trên người cậu. Trong cái thế giới mà Lục Đại Thánh Điện của loài người tranh đầu cùng Thất Thập Lục Trụ Ma Thần', 2022, 'Đang cập nhật', 'Đang cập nhật', NULL, 8.3, '', 'series', '', 'ThanAnVuongToa.png', 5, 7, 99999),
(7, ' Già Thiên', 'Một chàng trai thành đạt được bạn bè yêu quý. Trong một lần họp mặt cùng nhóm bạn, một sự cố huyền ảo đã xảy ra và đưa cả nhóm bạn quay trở về thời cổ đại, thời kỳ tồn tại của những vị thần. Nơi đó, mọi mâu thuẫn chỉ có thể giải quyết bằng sức mạnh…Có tồn tại thế giới thần tiên này ư? Có phải đây chỉ là những truyền thuyết hay sự tích ? Những gì nhìn thấy là sự thật hay là ảo mộng.\r\n\r\nPhim được chuyển thể từ tiểu thuyết “Già Thiên” của tác giả Thần Đông (Thế Giới Hoàn Mỹ) và cùng studio với Phàm Nhân Tu Tiên\r\nMain: Diệp Phàm\r\n\r\nThê tử: Cơ Tử Nguyệt\r\nHồng nhan: Tần Dao, An Diệu Y\r\n\r\nPhân chia cảnh giới\r\nLuân Hải bí cảnh:\r\nKhổ Hải\r\nMệnh Tuyền\r\nThần Kiều\r\nBỉ Ngạn\r\nĐạo Cung bí cảnh:\r\nTâm thần tàng\r\nCan thần tàng\r\nTỳ thần tàng\r\nPhế thần tàng\r\nThận thần tàng\r\nTứ Cực bí cảnh:\r\nTả Tí\r\nHữu Tí\r\nTả Thối\r\nHữu Thối\r\nHóa Long bí cảnh: Phân cửu biến, tu luyện cột sống, từ xương đuôi đến xương cổ. Khi đại viên mãn xương sống hiển hiện hóa long, tu sĩ phổ thông luyện đến bí cảnh này coi như tiểu thành\r\nTiên Đài bí cảnh:\r\nTầng một: Cấp bậc Thánh địa Thái Thượng Trưởng Lão, cô đọng thần thức cường đại, đỉnh phong xưng là nửa bước Đại Năng\r\nTầng hai: Thánh địa Thánh Chủ, Giáo Chủ đại giáo, hoàng triều Hoàng Chủ, xưng Đại Năng.\r\nTầng ba: Trảm đạo, xưng hào Vương Giả. Khi Vương Giả đại thành đạt nửa bước Thánh Vực thì được xưng Bán Thánh.\r\nTầng bốn: Sinh mệnh thăng hoa, đi vào pháp tắc lĩnh vực. Xưng hào Thánh Nhân\r\nTầng năm: Lĩnh vực pháp tắc càng thêm tinh thâm. Xưng hào Thánh Nhân Vương\r\nTầng sáu: Thánh đạo lĩnh vực pháp tắc đã đến cực hạn. Mỗi một nấc thang nhỏ đều có thể so với Hóa Long bí cảnh. Xưng hào Đại Thánh.\r\nChuẩn Đế: Phân cửu trọng thiên. Tu luyện ngũ đại bí cảnh, từ tinh thâm đến viên mãn. Thánh đạo pháp tắc thăng cấp chí tôn pháp tắc, tầng thứ chín ngũ đại bí cảnh đại viên mãn.\r\nĐại Đế: Đã chứng đạo, xưng Thiên Tôn, Cổ Hoàng. Ngũ đại bí cảnh viên mãn hợp nhất, hóa thành đạo kén, dựng dụng hoàng đạo pháp tắc\r\nHồng Trần Tiên: Trường sinh bất hủ, tuế nguyệt không ảnh hưởng bản thân', 2021, 'Đang cập nhật', 'Đang cập nhật', NULL, 8.3, '', 'series', '', 'gia-thien-poster-1.jpg', 5, 20, 99999),
(8, 'Tây Hành Kỷ – Ám Ảnh Ma Thành', 'Xem phim Tây Hành Kỷ – Ám Ảnh Ma Thành Vietsub 2023:\r\n“Tây Hành Kỷ – Ám Ảnh Ma Thành” kể về vị thành chủ thành Ám Ảnh Ma Anh, người đứng đầu Ám Ảnh Ma Thành, vì khống chế Đường Tam Tạng để cướp lấy Vĩnh Hằng Chi Hỏa, sử dụng năng lực ma thành có thể thỏa mãn tất cả dục vọng dẫn ra tâm ma của Đường Tam Tạng. Cuối cùng, Đường Tam Tạng vượt qua tâm ma, một lần nữa thức tỉnh, hắn cùng đồ đệ liên thủ đánh bại Ma Anh, tan rã ma thành gây họa một phương, một lần nữa bắt tay vào hành trình cứu vớt thiên hạ thương sinh.', 2023, 'Đang cập nhật', 'Đang cập nhật', NULL, 7.4, '//ok.ru/videoembed/6667086727899', 'movie', '//ok.ru/videoembed/6667086727899', 'tay-hanh-ky-am-anh-ma-thanh-300x450.jpg', 5, 0, 111111),
(9, 'Họa Giang Hồ – Thiên Cang', 'Vì sao ngày đó thủ lĩnh cao nhất của Bất Lương Nhân phải nghẹn ngào bật khóc? Vì sao bằng hữu tốt Lý Thuần Phong lại cho rằng thuốc trường sinh là trái với lẽ thường?Phim hoạt hình ngoại truyện “Viên Thiên Cang” đầu tiên trong loạt phim Họa Giang Hồ, ngày 29/12\r\n\r\n', 2023, 'Đang cập nhật', 'Đang cập nhật', NULL, 5.4, '<iframe width=\"1280\" height=\"720\" src=\"//ok.ru/videoembed/6667086727899\" frameborder=\"0\" allow=\"autoplay\" allowfullscreen></iframe>', 'movie', '<iframe width=\"1280\" height=\"720\" src=\"//ok.ru/videoembed/6667086727899\" frameborder=\"0\" allow=\"autoplay\" allowfullscreen></iframe>', 'hoa-giang-ho-thien-cang-300x450.jpg', 5, 0, 100),
(10, ' Phong Khởi Lạc Dương: Song Tử Truy Hung', 'Sau khi tình trạng hồ yêu hỗn loạn lắng xuống, kinh thành đã trở lại thịnh vượng và nhộn nhịp như trước đây, Vũ hoàng hậu có tâm trạng tốt và quyết định tổ chức một cuộc đi săn mùa thu thật hoành tráng, mời các hoàng tử, quý tộc và những người giỏi võ thuật tụ tập lại để thi đấu trên lưng ngựa và bắn cung. Mộ Dung Kỷ, khanh trẻ của Thổ Cốc Hỗn, cũng được mời tham gia cuộc đi săn mùa thu này, nhưng vô tình trở thành “nghi phạm” trong âm mưu ám sát hoàng hậu. Mộ Dung Kỷ không còn cách nào khác ngoài nhờ Bồi Khôn giúp đỡ, hy vọng anh có thể giúp tìm ra kẻ sát nhân thực sự và làm rõ danh tính của hắn. Để bảo vệ hòa bình của nhà Đường và dân chúng, đồng thời giúp đỡ bạn bè của mình, Bồi Khôn, Úy Trì Trụ, Hàn Đông Thanh và các cộng sự khác một lần nữa hợp lực điều tra vụ án và cuối cùng thủ phạm thực sự lên kế hoạch ám sát Vũ Hoàng hậu đã bị truy tìm, cuộc chiến giữa nhà Đường và Thổ Cốc Hỗn đã bị ngăn chặn.', 2023, 'Đang cập nhật', 'Đang cập nhật', NULL, 6.4, 'Đang cập nhật', 'movie', 'Đang cập nhật', 'phong-khoi-lac-duong-song-tu-truy-hung-300x450.jpg', 5, 0, 1231),
(11, ' Tru Tiên (Phần 2)', 'Chính ma đại chiến đã qua mười năm, Trương Tiểu Phàm hóa thân thành quỷ lệ, chinh phạt tứ phương trong nội đấu ma giáo. Lúc này dị bảo đầm lầy tử vong xuất thế, dẫn tới các thế lực tranh đoạt, Quỷ Lệ cùng Lục Tuyết Kỳ gặp lại, hai người lâm vào gút mắc yêu hận càng sâu.Sau đó Quỷ Lệ bởi vì chuyện cấp dưới mất tích mà điều tra tới Phần Hương cốc, ngoài ý muốn cứu ra Cửu Vĩ Thiên Hồ Tiểu Bạch, đạt được manh mối của Cổ Vu tộc,Quỷ Lệ trải qua cửu tử nhất sinh, cuối cùng cầu được đại vu sư nắm giữ chiêu hồn kỳ thuật đáp ứng đi tới cứu chữa Bích Dao, tâm nguyện mười năm, đến tột cùng kết quả như thế nào.', 2024, 'Đang cập nhật', 'Đang cập nhật', NULL, 8.4, '', 'series', '', 'tru-tien-phan-2-2024-300x450.jpg', 5, 27, 9999555),
(12, ' Thái Nhất Kiếm Tiên Truyện', 'Đại lục Cửu Châu, tiên đạo hưng thịnh, người đời đều lấy việc gia nhập Tiên môn làm vinh dự. Song, vết nứt không gian đột nhiên xuất hiện, bắt đầu có Ma tộc dị giới lén lút lẻn vào Tiên môn Cửu Châu. Sau khi xâm nhập vào các Tiên môn lớn, Ma tộc lên kế hoạch dần dần chiếm lấy quyền cai quản Tiên môn, mưu đồ lật đổ đại lục Cửu Châu. Kiếm tiên Mục Huân cùng các đạo hữu ngăn cản Ma tộc xâm phạm, nhưng lại bất hạnh trúng bẫy của bọn chúng, bị hiểu lầm thành phản đồ của Tiên môn.\r\n\r\nĐể tìm thời cơ, tìm manh mối về việc Ma tộc ẩn núp, Mục Huân quyết định mai danh ẩn tích, đồng thời giao đứa con trai duy nhất của mình là Đông Phương Hạo cho tộc nhân của thê tử nuôi dưỡng. Đông Phương Hạo có huyết mạch Đế tộc nhưng linh căn lại không hiện, nên vẫn luôn bị người trong tộc coi là kẻ vô dụng, chịu đủ mọi tủi nhục; vốn là con cháu của Kiếm tiên, nhưng vì cha bị Ma tộc hãm hại mà đường đời trở nên gập ghềnh hơn bất cứ ai. Vào thời khắc nguy cấp, dựa vào sự cố gắng không ngừng, Đông Phương Hạo luôn tìm ra cách hóa nguy thành an, bước từng bước trên con đường chính đạo, tu lên thành tiên.\r\n\r\nBí cảnh Thái Sơ, vương đô Chiêu Võ, chàng luôn dũng cảm đứng ra, nhiều lần cứu giúp bằng hữu khỏi nguy nan. Đồng thời, chàng cũng chưa từng quên việc phải đi tìm kiếm người cha mất tích của mình. Thuần hóa giao long, tranh Tiên phách, tu vi không ngừng nâng cao, Đông Phương Hạo quyết chí phải tìm được cha về, đồng thời xây dựng chỉnh đốn lại các Tiên môn lớn, đuổi Ma tộc ra khỏi đại lục Cửu Châu.', 2023, 'Đang cập nhật', 'Đang cập nhật', NULL, 6.4, 'Đang cập nhật', 'series', 'Đang cập nhật', 'Thai-Nhat-Kiem-Tien-Truyen-300x449.jpg', 5, 0, 436426),
(23, 'Băng Hỏa Ma Trù', 'Băng Hỏa Ma Trù The Magic Chef Of Ice And Fire 2021 Full HD Vietsub Thuyết Minh\r\nbộ phim hoạt hình của trung quốc kể về Nian Bing là con trai của một pháp sư lửa và một pháp sư băng. Sau khi cả cha và mẹ của mình đều bị giết bởi Ice Lord, Nian Bing đã nhận được cả hai viên ngọc phép thuật của cha mẹ mình. Khi Nian Bing cố gắng trốn thoát khỏi những người theo Chúa băng giá, anh ta đã thi triển cả ma thuật lửa và băng cùng một lúc. Một kỳ tích bất khả thi đối với một pháp sư. Anh rơi từ vách đá xuống, bất tỉnh và được một ông già cứu. Sau khi anh ta tỉnh dậy, ông lão đã cho anh ta một món ăn rất ngon mà anh ta chưa từng được nếm trước đây. Hóa ra ông lão là một đầu bếp thiên tài, từng được gọi là đầu bếp thần. Và anh ấy muốn Nian Bing là đệ tử của mình dù có thế nào đi nữa! Liệu Nian Bing có thể tìm kiếm sự báo thù trong khi nhắm mục tiêu trở thành đầu bếp vĩ đại nhất?\r\nHiện ít hơn ', 2021, 'Youku, Xuanshi Tangmen', 'Rong, Niệm Băng, Shen Manjun', NULL, 8, '//ok.ru/videoembed/7401068235403', 'series', '', 'bang-hoa-ma-tru.jpg', 5, 0, 45753),
(24, 'Vua hải tặc', 'Anime được phát sóng lần đầu tiên vào năm 1999 và nhanh chóng trở thành một trong những series anime phổ biến nhất trên thế giới. Câu chuyện xoay quanh Monkey D. Luffy, một chàng trai trẻ với giấc mơ trở thành Vua Hải Tặc. Luffy, người có khả năng co giãn như cao su sau khi ăn nhầm Trái Ác Quỷ, lãnh đạo nhóm Hải Tặc Mũ Rơm đi khắp Grand Line để tìm kiếm kho báu huyền thoại One Piece và theo đuổi giấc mơ của mình. Series nổi tiếng với cốt truyện phong phú, nhân vật đa dạng, và những pha hành động hấp dẫ', 1999, 'Uda Kounosuke, Ishitani Megumi', 'Đang cập nhật', NULL, 10, '', 'series', '', 'one-piece-dao-hai-tac-a1_1732071088.jpg', 4, 2, 500000);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `movie_genres`
--

CREATE TABLE `movie_genres` (
  `movie_id` int(11) NOT NULL,
  `genre_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `movie_genres`
--

INSERT INTO `movie_genres` (`movie_id`, `genre_id`) VALUES
(6, 8),
(6, 10),
(6, 11),
(7, 8),
(7, 10),
(8, 10),
(8, 12),
(9, 7),
(9, 8),
(9, 10),
(10, 3),
(10, 7),
(10, 8),
(10, 10),
(11, 3),
(11, 7),
(11, 8),
(11, 10),
(11, 11),
(12, 3),
(12, 7),
(12, 8),
(12, 10),
(12, 12),
(23, 6),
(23, 8),
(23, 10),
(23, 11),
(23, 12),
(24, 4),
(24, 5),
(24, 10),
(24, 12);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `purchased_movies`
--

CREATE TABLE `purchased_movies` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `movie_id` int(11) NOT NULL,
  `purchased_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci;

--
-- Đang đổ dữ liệu cho bảng `purchased_movies`
--

INSERT INTO `purchased_movies` (`id`, `user_id`, `movie_id`, `purchased_at`) VALUES
(1, 7, 8, '2025-05-12 13:51:18'),
(7, 7, 9, '2025-05-12 13:53:49'),
(10, 7, 11, '2025-05-12 19:36:34'),
(12, 2, 9, '2025-05-12 22:57:33'),
(13, 7, 10, '2025-05-16 02:44:05');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `ratings`
--

CREATE TABLE `ratings` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `movie_id` int(11) DEFAULT NULL,
  `rating` int(11) DEFAULT NULL CHECK (`rating` >= 1 and `rating` <= 5)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `saved_movies`
--

CREATE TABLE `saved_movies` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `movie_id` int(11) NOT NULL,
  `saved_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `saved_movies`
--

INSERT INTO `saved_movies` (`id`, `user_id`, `movie_id`, `saved_at`) VALUES
(3, 5, 10, '2024-08-25 16:21:29'),
(4, 5, 12, '2024-08-25 16:22:19'),
(5, 5, 6, '2024-08-25 16:30:08'),
(6, 2, 23, '2025-04-24 05:53:20'),
(7, 7, 11, '2025-04-24 18:52:03'),
(8, 7, 6, '2025-04-24 18:52:08');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `setting`
--

CREATE TABLE `setting` (
  `id` int(11) NOT NULL,
  `type` varchar(50) DEFAULT NULL,
  `value` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci;

--
-- Đang đổ dữ liệu cho bảng `setting`
--

INSERT INTO `setting` (`id`, `type`, `value`) VALUES
(1, 'stk_bank', 'TIENNVONET'),
(2, 'ctk_bank', 'VO TRAN TIEN'),
(3, 'api_bank', 'null'),
(4, 'token_bank', 'tiennvo');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `role` enum('admin','trans','user') DEFAULT 'user',
  `money` bigint(20) NOT NULL DEFAULT 0,
  `total_money` bigint(20) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `role`, `money`, `total_money`) VALUES
(2, 'admin', '$2y$10$hyu32w7PCcahZ41hSS3wG.k41ISBlY6PIpj.usWjSNO3wd6A8gvUK', 'admin@gmail.com', 'admin', 12245, 0),
(4, 'trans', '$2y$10$jxLYim6Pgq9cpUFT1fziXuBxTloSDUGR/Li5uJQ5FwIjET0cGcqHC', 'trans@gmail.com', 'trans', 0, 0),
(5, 'member', '$2y$10$051Ala6u3b4rfCsmymRnV..lx4AYBv/JwrLOOhCZ7p5GRVoK4QtWe', 'member@gmail.com', 'user', 0, 0),
(7, 'tiennvo', '$2y$10$yagxKGrb4S/xHFXT.081X.PHrrZomsi.c2X9m8n/1vLH6zaXqRZPK', 'phongj88@gmail.com', '', 19768, 20000),
(10, 'tri', '$2y$10$OghdTlxBnhnGw0FYRp8bKOwRgxGNodVJDj/D8Mrx4wyYInJqmH7fG', 'tridz@gmail.com', '', 0, 0);

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `bank_auto`
--
ALTER TABLE `bank_auto`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tid` (`tid`);

--
-- Chỉ mục cho bảng `buy_movies`
--
ALTER TABLE `buy_movies`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_save` (`user_id`,`movie_id`),
  ADD KEY `movie_id` (`movie_id`);

--
-- Chỉ mục cho bảng `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `movie_id` (`movie_id`);

--
-- Chỉ mục cho bảng `countries`
--
ALTER TABLE `countries`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Chỉ mục cho bảng `dongtien`
--
ALTER TABLE `dongtien`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `episodes`
--
ALTER TABLE `episodes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `movie_id` (`movie_id`);

--
-- Chỉ mục cho bảng `genres`
--
ALTER TABLE `genres`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Chỉ mục cho bảng `movies`
--
ALTER TABLE `movies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_country` (`country_id`);

--
-- Chỉ mục cho bảng `movie_genres`
--
ALTER TABLE `movie_genres`
  ADD PRIMARY KEY (`movie_id`,`genre_id`),
  ADD KEY `genre_id` (`genre_id`);

--
-- Chỉ mục cho bảng `purchased_movies`
--
ALTER TABLE `purchased_movies`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_purchase` (`user_id`,`movie_id`),
  ADD KEY `movie_id` (`movie_id`);

--
-- Chỉ mục cho bảng `ratings`
--
ALTER TABLE `ratings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `movie_id` (`movie_id`);

--
-- Chỉ mục cho bảng `saved_movies`
--
ALTER TABLE `saved_movies`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_save` (`user_id`,`movie_id`),
  ADD KEY `movie_id` (`movie_id`);

--
-- Chỉ mục cho bảng `setting`
--
ALTER TABLE `setting`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `bank_auto`
--
ALTER TABLE `bank_auto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `buy_movies`
--
ALTER TABLE `buy_movies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT cho bảng `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT cho bảng `countries`
--
ALTER TABLE `countries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `dongtien`
--
ALTER TABLE `dongtien`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `episodes`
--
ALTER TABLE `episodes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT cho bảng `genres`
--
ALTER TABLE `genres`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT cho bảng `movies`
--
ALTER TABLE `movies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT cho bảng `purchased_movies`
--
ALTER TABLE `purchased_movies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT cho bảng `ratings`
--
ALTER TABLE `ratings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `saved_movies`
--
ALTER TABLE `saved_movies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT cho bảng `setting`
--
ALTER TABLE `setting`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Ràng buộc đối với các bảng kết xuất
--

--
-- Ràng buộc cho bảng `buy_movies`
--
ALTER TABLE `buy_movies`
  ADD CONSTRAINT `fk_buy_movies_movie_id` FOREIGN KEY (`movie_id`) REFERENCES `movies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_buy_movies_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ràng buộc cho bảng `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`movie_id`) REFERENCES `movies` (`id`) ON DELETE CASCADE;

--
-- Ràng buộc cho bảng `episodes`
--
ALTER TABLE `episodes`
  ADD CONSTRAINT `episodes_ibfk_1` FOREIGN KEY (`movie_id`) REFERENCES `movies` (`id`) ON DELETE CASCADE;

--
-- Ràng buộc cho bảng `movies`
--
ALTER TABLE `movies`
  ADD CONSTRAINT `fk_country` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`) ON DELETE SET NULL;

--
-- Ràng buộc cho bảng `movie_genres`
--
ALTER TABLE `movie_genres`
  ADD CONSTRAINT `movie_genres_ibfk_1` FOREIGN KEY (`movie_id`) REFERENCES `movies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `movie_genres_ibfk_2` FOREIGN KEY (`genre_id`) REFERENCES `genres` (`id`) ON DELETE CASCADE;

--
-- Ràng buộc cho bảng `purchased_movies`
--
ALTER TABLE `purchased_movies`
  ADD CONSTRAINT `purchased_movies_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `purchased_movies_ibfk_2` FOREIGN KEY (`movie_id`) REFERENCES `movies` (`id`);

--
-- Ràng buộc cho bảng `ratings`
--
ALTER TABLE `ratings`
  ADD CONSTRAINT `ratings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `ratings_ibfk_2` FOREIGN KEY (`movie_id`) REFERENCES `movies` (`id`) ON DELETE CASCADE;

--
-- Ràng buộc cho bảng `saved_movies`
--
ALTER TABLE `saved_movies`
  ADD CONSTRAINT `saved_movies_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `saved_movies_ibfk_2` FOREIGN KEY (`movie_id`) REFERENCES `movies` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
