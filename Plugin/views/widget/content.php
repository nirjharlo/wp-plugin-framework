<?php echo esc_attr( $this->e( $before_widget ) );
echo esc_attr( $this->e( $before_title ) . $this->e( $title ) . $this->e( $after_title ) );
echo esc_html__( 'Hello, World!', 'textdomain' );
echo esc_attr( $this->e( $after_widget ) );
