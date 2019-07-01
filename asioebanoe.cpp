#define ASIO_STANDALONE
#include <asio.hpp>
#include <iostream>
#include <string.h>

		using asio::ip::tcp;

int main(int argc, char** argv) {

	setlocale(LC_ALL, "");

	if (argc < 2) {
		std::cout << "Мало аргументов, идите нахуй\n" << std::endl;
		return 1;
	}

	if (argv[1][0] == 'c') {
		try {
			asio::io_context io;
			tcp::resolver resolver(io);
			tcp::resolver::results_type endpoint = resolver.resolve("127.0.0.1", "80");

			tcp::socket socket(io);
			asio::connect(socket, endpoint);
			std::cout << "Подключено" << std::endl;

			std::string buffer;
			buffer.resize(4096);

			for (;;) {
				asio::error_code error;

				socket.read_some(asio::buffer(buffer, 4096));
				std::cout << buffer << std::endl;
				if (error == asio::error::eof) {
					continue;
				}
				if (error) {
					throw asio::system_error(error);
				}
			}

		}
		catch(std::exception& e){
			std::cerr << e.what() << std::endl;
		}
	}
	else if (argv[1][0] == 's') {
		try {
			asio::io_context io;
			tcp::acceptor acceptor(io, tcp::endpoint(tcp::v4(), 80));

			std::string buffer = "Вы подключены к серверу. Делаете здесь грязные вещи\n";
			tcp::socket socket(io);

			for (;;) {
				std::cout << "Ожидаем подключение" << std::endl;
				acceptor.accept(socket);
				std::cout << "Клиент подключен" << std::endl;
				
				socket.write_some(asio::buffer(buffer));

				asio::error_code ignored_error;
			}
		}
		catch (std::exception& e) {
			std::cout << e.what() << std::endl;
		}
	}

	return 0;
}